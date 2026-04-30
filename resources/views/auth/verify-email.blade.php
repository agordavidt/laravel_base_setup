@extends('layouts.guest')
@section('title', 'Verify Email')

@section('content')
<div class="w-full max-w-md fade-up">
   
    <div class="card-glow rounded-2xl p-8 text-center" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        {{-- Icon --}}
        <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h2 class="font-display text-white text-2xl mb-3">Check your inbox</h2>
        <p class="text-white/35 text-sm leading-relaxed mb-6">
            We've sent a verification link to your email address. Click the link to activate your account.
        </p>

        {{-- Resend confirmation --}}
        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 text-sm text-green-400 bg-green-400/10 border border-green-400/20 rounded-lg px-4 py-3">
                A new verification link has been sent to your email.
            </div>
        @endif

        <form method="POST" action="">
            @csrf
            <button type="submit" class="btn-gold text-navy font-semibold px-8 py-3 rounded-xl text-sm">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="/logout" class="mt-5">
            @csrf
            <button type="submit" class="text-white/25 text-sm hover:text-white/50 transition">
                Log Out
            </button>
        </form>

    </div>

</div>
@endsection
