@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
<div class="w-full max-w-md fade-up">

    <div class="card-glow rounded-2xl p-8"
         style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            {{-- Token passed from FortifyServiceProvider::resetPasswordView() --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block mb-2">Email Address</label>
                {{-- $email passed explicitly from FortifyServiceProvider --}}
                <input id="email" type="email" name="email"
                       value="{{ old('email', $email) }}"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="you@example.com" required autofocus autocomplete="email">
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block mb-2">New Password</label>
                <input id="password" type="password" name="password"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="Min. 8 characters" required autocomplete="new-password">
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block mb-2">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       class="input-field w-full rounded-xl px-4 py-3 text-sm"
                       placeholder="Repeat new password" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-gold w-full text-navy font-semibold py-3 rounded-xl text-sm">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection