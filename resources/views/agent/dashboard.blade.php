@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Welcome banner --}}
<div class="rounded-2xl p-8 mb-8"
     style="background: linear-gradient(135deg, rgba(201,168,76,0.08) 0%, rgba(201,168,76,0.02) 100%); border: 1px solid rgba(201,168,76,0.15);">
    <p class="text-white/40 text-xs tracking-widest uppercase mb-1">Welcome back</p>
    <h2 class="font-display text-white text-3xl">{{ auth()->user()->name ?? 'Guest' }} 👋</h2>
    <p class="text-white/30 text-sm mt-2">You're logged in and ready to go.</p>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-3 gap-5 mb-8">

    <div class="rounded-xl p-6" style="background: rgba(255,255,255,.025); border: 1px solid rgba(255,255,255,.07);">
        <p class="text-white/30 text-xs uppercase tracking-widest mb-2">Account Status</p>
        <p class="text-white font-medium text-lg">Active</p>
        <span class="inline-block mt-2 text-xs text-green-400 bg-green-400/10 px-2 py-0.5 rounded-full">Verified</span>
    </div>

    <div class="rounded-xl p-6" style="background: rgba(255,255,255,.025); border: 1px solid rgba(255,255,255,.07);">
        <p class="text-white/30 text-xs uppercase tracking-widest mb-2">Member Since</p>
        <p class="text-white font-medium text-lg">{{ auth()->user()->created_at?->format('M Y') ?? '—' }}</p>
    </div>

    <div class="rounded-xl p-6" style="background: rgba(255,255,255,.025); border: 1px solid rgba(255,255,255,.07);">
        <p class="text-white/30 text-xs uppercase tracking-widest mb-2">2FA</p>
        <p class="text-white font-medium text-lg">
            {{ auth()->user()->two_factor_confirmed_at ? 'Enabled' : 'Disabled' }}
        </p>
        <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full
            {{ auth()->user()->two_factor_confirmed_at ? 'text-green-400 bg-green-400/10' : 'text-yellow-500/70 bg-yellow-500/10' }}">
            {{ auth()->user()->two_factor_confirmed_at ? 'Secure' : 'Not set up' }}
        </span>
    </div>

</div>

{{-- Info box --}}
<div class="rounded-xl p-6" style="background: rgba(255,255,255,.02); border: 1px solid rgba(255,255,255,.06);">
    <h3 class="text-white/60 text-sm font-medium mb-4 uppercase tracking-widest">Account Details</h3>
    <div class="space-y-3">
        <div class="flex justify-between items-center py-3 border-b border-white/5">
            <span class="text-white/30 text-sm">Name</span>
            <span class="text-white/70 text-sm">{{ auth()->user()->name ?? '—' }}</span>
        </div>
        <div class="flex justify-between items-center py-3 border-b border-white/5">
            <span class="text-white/30 text-sm">Email</span>
            <span class="text-white/70 text-sm">{{ auth()->user()->email ?? '—' }}</span>
        </div>
        <div class="flex justify-between items-center py-3">
            <span class="text-white/30 text-sm">Email Verified</span>
            <span class="text-sm {{ auth()->user()->email_verified_at ? 'text-green-400' : 'text-yellow-500' }}">
                {{ auth()->user()->email_verified_at ? 'Yes – ' . auth()->user()->email_verified_at->format('d M Y') : 'Not verified' }}
            </span>
        </div>
    </div>
</div>

@endsection