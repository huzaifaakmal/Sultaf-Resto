<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Menu;
use App\Models\TransaksiItem;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WaiterOrderController extends Controller
{
    /**
     * Get real-time status for all restaurant tables.
     */
    public function tables(): JsonResponse
    {
        $tenantId = app()->bound('current_tenant_id') ? app('current_tenant_id') : 1;

        // Fetch active dine-in orders
        $activeOrders = TransaksiPenjualan::where('tenant_id', $tenantId)
            ->where('tipe_pesanan', 'dine_in')
            ->whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'served'])
            ->get()
            ->keyBy('nomor_meja');

        $tables = [];
        for ($i = 1; $i <= 20; $i++) {
            $order = $activeOrders->get($i);
            $tables[] = [
                'table_number' => $i,
                'status'       => $order ? 'occupied' : 'available',
                'order_code'   => $order?->kode_transaksi,
                'order_status' => $order?->status_pesanan,
                'guest_name'   => $order?->nama_pelanggan,
                'total_amount' => $order?->total_harga,
            ];
        }

        return response()->json([
            'success' => true,
            'tables'  => $tables,
        ], 200);
    }

    /**
     * Create table-side order from Waiter app (supports offline sync replay).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomor_meja'        => ['required', 'integer', 'min:1', 'max:20'],
            'nama_pelanggan'    => ['required', 'string', 'max:255'],
            'no_telepon'        => ['nullable', 'string', 'max:20'],
            'catatan'           => ['nullable', 'string', 'max:500'],
            'items'             => ['required', 'array', 'min:1'],
            'items.*.menu_id'   => ['required', 'exists:menus,id'],
            'items.*.qty'       => ['required', 'integer', 'min:1'],
            'client_order_uuid' => ['nullable', 'string', 'max:100'],
        ]);

        $tenantId = app()->bound('current_tenant_id') ? app('current_tenant_id') : 1;

        $order = DB::transaction(function () use ($validated, $tenantId, $request) {
            $menuIds = collect($validated['items'])->pluck('menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

            $subtotal = 0;
            foreach ($validated['items'] as $itemData) {
                $menu = $menus->get($itemData['menu_id']);
                if ($menu) {
                    $subtotal += (float) $menu->harga_makanan * $itemData['qty'];
                }
            }

            $tax = round($subtotal * 0.10);
            $total = $subtotal + $tax;

            $transaksi = TransaksiPenjualan::create([
                'tenant_id'         => $tenantId,
                'kode_transaksi'    => 'WTR-' . strtoupper(Str::random(6)),
                'user_id'           => $request->user()?->id,
                'nama_pelanggan'    => $validated['nama_pelanggan'],
                'no_telepon'        => $validated['no_telepon'] ?? null,
                'tipe_pesanan'      => 'dine_in',
                'nomor_meja'        => $validated['nomor_meja'],
                'subtotal'          => $subtotal,
                'pajak'             => $tax,
                'service_charge'    => 0,
                'diskon_member'     => 0,
                'total_harga'       => $total,
                'status_pesanan'    => 'pending',
                'status_pembayaran' => 'menunggu',
                'catatan'           => $validated['catatan'] ?? null,
                'tgl_transaksi'     => now(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $menu = $menus->get($itemData['menu_id']);
                if ($menu) {
                    TransaksiItem::create([
                        'transaksi_penjualan_id' => $transaksi->id,
                        'menu_id'                => $menu->id,
                        'qty'                    => $itemData['qty'],
                        'harga_satuan'           => $menu->harga_makanan,
                    ]);
                }
            }

            // Audit Trail
            AuditLog::create([
                'tenant_id'      => $tenantId,
                'user_id'        => $request->user()?->id,
                'action'         => 'WAITER_ORDER_CREATED',
                'auditable_type' => TransaksiPenjualan::class,
                'auditable_id'   => $transaksi->id,
                'new_values'     => [
                    'kode'  => $transaksi->kode_transaksi,
                    'table' => $transaksi->nomor_meja,
                    'total' => $transaksi->total_harga,
                ],
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent() ?? 'Waiter-PWA',
            ]);

            return $transaksi;
        });

        $order->load('items.menu');

        return response()->json([
            'success' => true,
            'message' => 'Order successfully placed by waiter.',
            'data'    => $order,
        ], 201);
    }

    /**
     * Fetch order details.
     */
    public function show(string $id): JsonResponse
    {
        $order = TransaksiPenjualan::with('items.menu')
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('kode_transaksi', $id);
            })
            ->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $order], 200);
    }
}
