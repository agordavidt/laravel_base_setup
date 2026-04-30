@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
<div class="w-full max-w-md fade-up">

    
    <div class="card-glow rounded-2xl p-8" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        <p class="text-white/40 text-sm mb-6 leading-relaxed">
            Enter the email address linked to your account and we'll send you a password reset link.
        </p>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-5 text-sm text-green-400 bg-green-400/10 border border-green-400/20 rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="you@example.com" required autofocus>
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm">
                Send Reset Link
            </button>
        </form>

    </div>

    <p class="text-center text-white/30 text-sm mt-6">
        Remember your password?
        <a href="/login" class="link-gold ml-1">Sign in</a>
    </p>

</div>
@endsection
