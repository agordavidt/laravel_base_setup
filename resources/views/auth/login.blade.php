@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="w-full max-w-md fade-up">

    {{-- Logo / heading --}}
    <div class="text-center mb-10">
        <a href="/" class="font-display text-gold text-3xl tracking-widest uppercase">Frontrow</a>
        <p class="text-white/30 text-sm mt-2">Welcome back. Sign in to continue.</p>
    </div>

    {{-- Card --}}
    <div class="card-glow rounded-2xl p-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-5 text-sm text-green-400 bg-green-400/10 border border-green-400/20 rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="you@example.com" required autofocus>
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-2">Password</label>
                <input id="password" type="password" name="password"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="••••••••" required>
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer" style="text-transform:none; font-size:.85rem; color:rgba(255,255,255,.45); letter-spacing:normal;">
                    <input type="checkbox" name="remember" class="rounded accent-yellow-500">
                    Remember me
                </label>
                <a href="/forgot-password" class="link-gold text-sm">Forgot password?</a>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm mt-2">
                Sign In
            </button>
        </form>

    </div>

    {{-- Register link --}}
    <p class="text-center text-white/30 text-sm mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="link-gold ml-1">Create one</a>
    </p>

</div>
@endsection