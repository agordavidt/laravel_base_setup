@extends('layouts.guest')
@section('title', 'Confirm Password')

@section('content')
<div class="w-full max-w-md fade-up">

    <div class="text-center mb-10">
        <a href="/" class="font-display text-gold text-3xl tracking-widest uppercase">Frontrow</a>
        <p class="text-white/30 text-sm mt-2">Security Check</p>
    </div>

    <div class="card-glow rounded-2xl p-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        <div class="flex items-center gap-3 mb-6 p-4 rounded-xl" style="background: rgba(201,168,76,.06); border: 1px solid rgba(201,168,76,.15);">
            <svg class="w-5 h-5 text-gold shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <p class="text-white/50 text-sm leading-relaxed">
                This is a secure area. Please confirm your password before continuing.
            </p>
        </div>

        <form method="POST" action="" class="space-y-5">
            @csrf

            <div>
                <label for="password" class="block mb-2">Current Password</label>
                <input id="password" type="password" name="password"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="••••••••" required autofocus>
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm">
                Confirm Password
            </button>
        </form>

    </div>

</div>
@endsection
