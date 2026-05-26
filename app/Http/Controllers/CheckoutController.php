<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'order_number' => 'SLTF-' . strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_price' => $total,
            'payment_status' => 'pending',
        ]);

        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('payment.index', $order->order_number);
    }

    public function payment($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order_number);
        }

        // Use the real QRIS image provided
        $qrUrl = asset('images/qris.jpg');

        return view('payment', compact('order', 'qrUrl'));
    }

    public function simulate($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $order->update(['payment_status' => 'paid']);

        return redirect()->route('order.success', $order->order_number);
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return view('success', compact('order'));
    }
}
