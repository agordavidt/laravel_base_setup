@extends('layouts.guest')
@section('title', 'Two-Factor Authentication')

@section('content')
<div class="w-full max-w-md fade-up">

    <div class="text-center mb-10">
        <a href="/" class="font-display text-gold text-3xl tracking-widest uppercase">Frontrow</a>
        <p class="text-white/30 text-sm mt-2">Two-Factor Authentication</p>
    </div>

    <div class="card-glow rounded-2xl p-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        {{-- Toggle hint --}}
        <div x-data="{ recovery: false }">

            <p class="text-white/40 text-sm mb-6 leading-relaxed" x-show="!recovery">
                Enter the 6-digit code from your authenticator app to confirm your identity.
            </p>
            <p class="text-white/40 text-sm mb-6 leading-relaxed" x-show="recovery" style="display:none">
                Enter one of your emergency recovery codes to regain access to your account.
            </p>

            <form method="POST" action="" class="space-y-5">
                @csrf

                {{-- Code field --}}
                <div x-show="!recovery">
                    <label for="code" class="block mb-2">Authentication Code</label>
                    <input id="code" type="text" inputmode="numeric" name="code"
                           class="input-field w-full rounded-xl px-4 py-3 text-sm tracking-widest text-center text-lg"
                           placeholder="000 000" autocomplete="one-time-code">
                    @error('code')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recovery code field --}}
                <div x-show="recovery" style="display:none">
                    <label for="recovery_code" class="block mb-2">Recovery Code</label>
                    <input id="recovery_code" type="text" name="recovery_code"
                           class="input-field w-full rounded-xl px-4 py-3 text-sm font-mono"
                           placeholder="xxxx-xxxx-xxxx" autocomplete="one-time-code">
                    @error('recovery_code')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm">
                    Verify
                </button>

            </form>

            {{-- Toggle between code / recovery --}}
            <p class="text-center mt-5 text-sm text-white/30">
                <button type="button"
                    class="link-gold text-sm cursor-pointer"
                    x-on:click="recovery = !recovery">
                    <span x-show="!recovery">Use a recovery code instead</span>
                    <span x-show="recovery" style="display:none">Use an authentication code instead</span>
                </button>
            </p>

        </div>
    </div>

</div>

{{-- Alpine.js for toggle (optional) --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
