@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="w-full max-w-md fade-up">

    {{-- Heading --}}
    <div class="text-center mb-10">
        <a href="/" class="font-display text-gold text-3xl tracking-widest uppercase">Frontrow</a>
        <p class="text-white/30 text-sm mt-2">Create your account. It's free.</p>
    </div>

    {{-- Card --}}
    <div class="card-glow rounded-2xl p-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block mb-2">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="John Doe" required autofocus>
                @error('name')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="you@example.com" required>
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-2">Password</label>
                <input id="password" type="password" name="password"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="Min. 8 characters" required>
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="Repeat password" required>
                @error('password_confirmation')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm mt-2">
                Create Account
            </button>

        </form>
    </div>

    {{-- Login link --}}
    <p class="text-center text-white/30 text-sm mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="link-gold ml-1">Sign in</a>
    </p>

</div>
@endsection