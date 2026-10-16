<x-layouts.app title="{{ __('Payment') }} - Sultaf">

    <div class="px-5 lg:px-10 xl:px-16 pt-6">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('checkout.details') }}" class="text-sultaf-ink">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                </a>
                <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Checkout') }}</h1>
            </div>
            @include('components.lang-switch')
        </div>

        <x-checkout-steps :current="3" />

        @if ($errors->any())
            <div class="mb-4 bg-red-50 rounded-xl px-4 py-3 text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.payment.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <div class="flex-1 min-w-0">
                    <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-4">{{ __('Payment Method') }}</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                        @php
                            $methods = [
                                'gopay' => ['label' => 'GoPay', 'color' => 'text-emerald-600 bg-emerald-50', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>'],
                                'ovo'   => ['label' => 'OVO',   'color' => 'text-purple-600 bg-purple-50',   'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'],
                                'dana'  => ['label' => 'DANA',  'color' => 'text-blue-600 bg-blue-50',     'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>'],
                                'qris'  => ['label' => __('Pay via QRIS'), 'color' => 'text-gray-800 bg-gray-100', 'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>'],
                                'cash'  => ['label' => __('Cash'),  'color' => 'text-amber-600 bg-amber-50',   'icon' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>'],
                            ];
                        @endphp
                        @foreach ($methods as $value => $opt)
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="{{ $value }}"
                                       class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                                <div class="flex items-center gap-3 border border-sultaf-border rounded-xl px-4 py-3.5
                                            peer-checked:border-sultaf-maroon peer-checked:bg-sultaf-maroon/5 transition-colors">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $opt['color'] }}">
                                        {!! $opt['icon'] !!}
                                    </div>
                                    <span class="font-medium text-sultaf-ink">{{ $opt['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-sultaf-ink mb-2">{{ __('Upload Payment Proof') }}</label>
                        <label id="uploadLabel" class="flex flex-col items-center justify-center gap-2 border-2 border-dashed
                                       border-sultaf-border rounded-xl py-8 cursor-pointer hover:border-sultaf-maroon transition-colors">
                            <svg class="w-7 h-7 text-sultaf-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M12 16V4M7 9l5-5 5 5M5 20h14"/>
                            </svg>
                            <span id="uploadText" class="text-sm text-sultaf-muted font-medium">{{ __('Upload Payment Proof') }}</span>
                            <span class="text-xs text-sultaf-muted">{{ __('JPG, PNG, or PDF, max 5MB') }}</span>
                            <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" class="hidden"
                                   onchange="document.getElementById('uploadText').textContent = this.files[0]?.name ?? '{{ __('Upload Payment Proof') }}'">
                        </label>
                    </div>

                    <h3 class="font-semibold text-sultaf-ink mb-3">{{ __('Customer Information') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Full Name') }}</label>
                            <input type="text" name="nama_pelanggan"
                                   value="{{ old('nama_pelanggan', auth()->user()?->name ?? '') }}"
                                   placeholder="e.g. Abdullah Hasan" class="input-field" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Phone Number') }}</label>
                            <input type="text" name="no_telepon"
                                   value="{{ old('no_telepon', auth()->user()?->phone ?? '') }}"
                                   placeholder="+62 812 3456 789" class="input-field" required>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-80 xl:w-96 shrink-0 lg:sticky lg:top-24">
                    <div class="bg-sultaf-maroon text-white rounded-2xl p-5">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-lg">{{ __('Order Summary') }}</span>
                            <span class="text-xs bg-white/20 rounded-full px-2.5 py-1">
                                {{ array_sum(session('cart', [])) }} {{ __('items') }}
                            </span>
                        </div>
                        <div class="space-y-2.5 text-sm border-b border-white/20 pb-4 mb-4">
                            <div class="flex justify-between">
                                <span class="text-white/70">{{ __('Subtotal') }}</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/70">{{ __('Tax (10%)') }}</span>
                                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            @if ($diskon > 0)
                                @php $diskonPercent = $subtotal > 0 ? round($diskon / $subtotal * 100) : 0; @endphp
                                <div class="flex justify-between text-amber-200">
                                    <span>{{ __('Member Discount') }} ({{ $diskonPercent }}%)</span>
                                    <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm uppercase tracking-wide">{{ __('Total Amount') }}</span>
                            <span class="font-serif text-2xl font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit"
                            class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl py-3.5
                                   w-full flex items-center justify-center gap-2 mt-4 transition-colors">
                        {{ __('Complete Payment') }}
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
