<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WaiterOrderController extends Controller
{
    private const TABLE_COUNT = 20;

    /**
     * Real-time status for all restaurant tables.
     */
    public function tables(): JsonResponse
    {
        $activeOrders = TransaksiPenjualan::where('tipe_pesanan', 'dine_in')
            ->whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'diantar'])
            ->get()
            ->keyBy(fn ($order) => (int) $order->no_meja);

        $tables = [];
        for ($i = 1; $i <= self::TABLE_COUNT; $i++) {
            $order = $activeOrders->get($i);
            $tables[] = [
                'table_number' => $i,
                'status'       => $order ? 'occupied' : 'available',
                'order_code'   => $order?->kode_transaksi,
                'order_status' => $order?->status_pesanan,
                'guest_name'   => $order?->nama_pelanggan,
                'total_amount' => $order?->total_bayar,
            ];
        }

        return response()->json(['success' => true, 'tables' => $tables], 200);
    }

    /**
     * Create a table-side order from the waiter app.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nomor_meja'      => ['required', 'integer', 'min:1', 'max:' . self::TABLE_COUNT],
            'nama_pelanggan'  => ['required', 'string', 'max:255'],
            'no_telepon'      => ['nullable', 'string', 'max:20'],
            'catatan'         => ['nullable', 'string', 'max:500'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id_menu'],
            'items.*.qty'     => ['required', 'integer', 'min:1'],
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $menus = Menu::whereIn('id_menu', collect($validated['items'])->pluck('menu_id'))
                ->get()
                ->keyBy('id_menu');

            $subtotal = collect($validated['items'])
                ->sum(fn ($item) => $menus[$item['menu_id']]->harga * $item['qty']);
            $total = $subtotal + round($subtotal * 0.10);

            $transaksi = TransaksiPenjualan::create([
                'kode_transaksi'    => 'WTR-' . strtoupper(Str::random(6)),
                'id_user'           => $request->user()?->id_user,
                'nama_pelanggan'    => $validated['nama_pelanggan'],
                'no_telepon'        => $validated['no_telepon'] ?? null,
                'tipe_pesanan'      => 'dine_in',
                'no_meja'           => (string) $validated['nomor_meja'],
                'diskon'            => 0,
                'total_bayar'       => $total,
                'status_pesanan'    => 'pending',
                'status_pembayaran' => 'menunggu',
                'metode_pembayaran' => 'cash',
                'catatan'           => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $menu = $menus[$item['menu_id']];

                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_menu'      => $menu->id_menu,
                    'jumlah'       => $item['qty'],
                    'harga_satuan' => $menu->harga,
                    'subtotal'     => $menu->harga * $item['qty'],
                ]);
            }

            return $transaksi;
        });

        $order->load('detail_transaksi.menu');

        return response()->json([
            'success' => true,
            'message' => 'Order successfully placed by waiter.',
            'data'    => $order,
        ], 201);
    }

    /**
     * Order details, looked up by numeric id or order code.
     */
    public function show(string $id): JsonResponse
    {
        $order = TransaksiPenjualan::with('detail_transaksi.menu')
            ->where('kode_transaksi', $id)
            ->orWhere('id_transaksi', ctype_digit($id) ? (int) $id : 0)
            ->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $order], 200);
    }
}
