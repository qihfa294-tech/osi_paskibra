<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Alumni Dashboard - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; -webkit-font-smoothing:antialiased}
.bg-paskibra{background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97);backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border:1px solid rgba(255,255,255,0.2);}
.card-inner{background:white; border:1px solid #f1f1f1; transition:all .22s ease; will-change:transform; transform:translateZ(0)}
.card-inner:hover{transform:translateY(-3px) translateZ(0); box-shadow:0 12px 30px rgba(0,0,0,.11)}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
.gshock{font-family:'JetBrains Mono', monospace; font-weight:800; letter-spacing:0.06em; font-variant-numeric: tabular-nums;}
.dot{animation: blink 1s steps(1) infinite}
@keyframes blink{0%,50%{opacity:1}51%,100%{opacity:0.2}}
.cal-day{width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:10px;border-radius:9999px}
.cal-day.today{background:#9a0000;color:white;font-weight:800}
@keyframes notifShrink { from { transform: scaleX(1); } to { transform: scaleX(0); } }
@keyframes notifOut { to { opacity: 0; transform: translateY(-6px); } }
#notifAlumni.hide { animation: notifOut .3s ease forwards; }
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? auth()->user()->name ?? auth()->user()->username ?? 'Alumni';
$namaFullUpper = Str::upper($namaFull);
$namaFirstShort = Str::limit($namaFullUpper, 12, '');
$angkatan = $angkatanFix ?? auth()->user()->angkatan_masuk ?? '2023';
$totalPps = $totalPps ?? 0;
$totalPpl = $totalPpl ?? 0;
$totalCapas = $totalCapas ?? $totalCapasSeangkatan ?? 0;
$totalAlumni = $totalAlumni ?? 0;
$totalAll = $totalPps + $totalPpl + $totalCapas + $totalAlumni;
$kelolaOn = isFeatureEnabled('kelola_anggota');
// E-KAS: coba 3 nama key yang paling sering kepake, biar gak salah key lagi
$ekasOn = isFeatureEnabled('e_kas') || isFeatureEnabled('ekas') || isFeatureEnabled('e-kas');
$lockIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>';
// Link E-KAS alumni - otomatis ngikutin route yang ada, biar gak error 500 kalo nama route beda
$ekasUrl = \Illuminate\Support\Facades\Route::has('alumni.ekas') ? route('alumni.ekas') : (\Illuminate\Support\Facades\Route::has('ekas.index') ? route('ekas.index') : url('/e-kas'));
@endphp

<div class="lg:hidden flex justify-between items-center p-4 sticky top-0 z-20 bg-[#7a0000] border-b border-white/10">
  <div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-8 h-8 object-contain"><span class="font-[800] italic text-white text-[14px]">OSI</span></div>
  <button id="btnOpen" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg></button>
</div>
<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">
  <div id="sidebar" class="w-[85%] max-w-[280px] lg:w-[280px] bg-[#4a0000] lg:bg-black/30 backdrop-blur-xl border-r border-white/10 fixed inset-y-0 left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col h-[100dvh] lg:h-screen">
    <div class="p-7 flex-1 overflow-y-auto no-scrollbar">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-10 h-10 object-contain drop-shadow"><div><h1 class="font-[800] text-white text-[16px] italic leading-none">OSI</h1><p class="text-[10px] font-bold tracking-[0.2em] text-white/60 mt-1">BY PASKIBRA NESTA</p></div></div>
        <button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
      </div>
      <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">ALUMNI MENU</p>
        <div class="space-y-1.5">
          <a href="{{ route('alumni.dashboard') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
          @if($kelolaOn)
          <a href="{{ route('alumni.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Lihat Anggota</a>
          @else
          <span class="flex items-center justify-between text-white/30 px-4 py-3 rounded-full text-[13px] cursor-not-allowed"><span class="flex items-center gap-3"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Lihat Anggota</span><span class="text-white/40">{!! $lockIcon !!}</span></span>
          @endif

          {{-- E-KAS SIDEBAR - BARU --}}
          @if($ekasOn)
          <a href="{{ $ekasUrl }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>E-KAS <span class="ml-auto text-[9px] bg-white/10 px-2 py-0.5 rounded-full">VIEW</span></a>
          @else
          <span class="flex items-center justify-between text-white/30 px-4 py-3 rounded-full text-[13px] cursor-not-allowed"><span class="flex items-center gap-3"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg>E-KAS</span><span class="text-white/40">{!! $lockIcon !!}</span></span>
          @endif
        </div>
      </div>
      <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
        <p class="text-[10px] font-bold tracking-widest text-amber-300">ALUMNI • {{ $angkatan }}</p>
        <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Akun Alumni hanya bisa melihat. Tidak bisa tambah, edit, atau hapus data.</p>
      </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0">
      <div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10">
        <div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ $namaFirstShort }}</p><p class="text-white/40 text-[9px] mt-1">ALUMNI • {{ $angkatan }}</p></div></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></button></form>
      </div>
    </div>
  </div>

  <div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen">
    <div class="mb-4">
      <p class="text-white text-[18px] lg:text-[22px] font-[800]">Hi, {{ $namaFullUpper }}</p>
      <p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase">Alumni • Angkatan {{ $angkatan }} • {{ $namaFull }}</p>
    </div>

    @if(session('success') || session('error'))
    @php $notifMsg = session('success') ?? session('error'); $isError = session('error'); @endphp
    <div id="notifAlumni" class="relative overflow-hidden rounded-[14px] px-4 py-3 mb-4 {{ $isError ? 'bg-[#FFF1F1] border border-red-100' : 'bg-emerald-50 border border-emerald-100' }}">
      <div class="flex items-center gap-2.5">
        <p class="text-[12px] font-bold {{ $isError ? 'text-[#7a0000]' : 'text-emerald-800' }}">{{ $notifMsg }}</p>
        <button type="button" onclick="document.getElementById('notifAlumni').remove()" class="ml-auto text-zinc-400 text-[18px] leading-none">×</button>
      </div>
      <div class="absolute bottom-0 left-0 h-[3px] {{ $isError ? 'bg-[#9a0000]' : 'bg-emerald-600' }} w-full origin-left" style="animation: notifShrink 5s linear forwards"></div>
    </div>
    <script>setTimeout(()=>{const n=document.getElementById('notifAlumni'); if(n){n.classList.add('hide'); setTimeout(()=>n.remove(),300)}},5000)</script>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalPps }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPL</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalPpl }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalCapas }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">ALUMNI</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalAlumni }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.7fr_0.9fr] gap-4 mb-4">
      <div class="glass-card rounded-[22px] p-5">
        <div class="flex justify-between items-start">
          <div><p class="text-[10px] font-bold tracking-widest text-zinc-400">TOTAL ANGGOTA</p><p class="text-[36px] font-[800] leading-none mt-2">{{ $totalAll }}</p><p class="text-[11px] text-zinc-500 mt-2">PPS • PPL • CAPAS • ALUMNI</p></div>
          <div class="w-11 h-11 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 mt-5">
          <div class="rounded-[14px] bg-emerald-50 border border-emerald-100 p-3"><p class="text-[9px] font-bold tracking-widest text-emerald-600">PPS</p><p class="text-[20px] font-[800] mt-1 text-emerald-700">{{ $totalPps }}</p><p class="text-[10px] text-emerald-600/70">Anggota</p></div>
          <div class="rounded-[14px] bg-red-50 border border-red-100 p-3"><p class="text-[9px] font-bold tracking-widest text-[#9a0000]">PPL</p><p class="text-[20px] font-[800] mt-1 text-[#9a0000]">{{ $totalPpl }}</p><p class="text-[10px] text-[#9a0000]/60">Anggota</p></div>
          <div class="rounded-[14px] bg-white border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[20px] font-[800] mt-1 text-zinc-800">{{ $totalCapas }}</p><p class="text-[10px] text-zinc-500">Anggota</p></div>
          <div class="rounded-[14px] bg-zinc-50 border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-500">ALUMNI</p><p class="text-[20px] font-[800] mt-1 text-zinc-800">{{ $totalAlumni }}</p><p class="text-[10px] text-zinc-500">Anggota</p></div>
        </div>
        <div class="mt-4 rounded-[14px] bg-zinc-900 text-white p-4 flex justify-between items-center">
          <div><p class="text-[9px] font-bold tracking-widest text-white/50">STATUS KAMU</p><p class="text-[13px] font-bold mt-1">Lulus • Alumni • {{ $angkatan }}</p></div>
          <span class="text-[9px] bg-[#9a0000] text-white px-3 py-1.5 rounded-full font-bold">VIEW-ONLY</span>
        </div>
      </div>

      <div class="glass-card rounded-[22px] overflow-hidden flex flex-col">
        <div class="bg-zinc-900 px-5 py-3 flex justify-between items-center">
          <p id="calTitle" class="text-[11px] font-bold tracking-widest text-white/60 uppercase">NOVEMBER 2026</p>
          <div class="flex gap-1">
            <button id="prevMonth" class="w-6 h-6 rounded-full bg-white/10 text-white flex items-center justify-center text-[11px]">‹</button>
            <button id="nextMonth" class="w-6 h-6 rounded-full bg-white/10 text-white flex items-center justify-center text-[11px]">›</button>
          </div>
        </div>
        <div class="bg-[#0e0e0e] m-3 rounded-[14px] p-4 border border-zinc-800">
          <div class="flex justify-between items-start">
            <div>
              <p class="text-[9px] tracking-widest text-white/30 font-bold">WAKTU INDONESIA BARAT</p>
              <p id="gshockTime" class="gshock text-[32px] leading-none text-white mt-1">00:00:00</p>
              <p id="gshockFull" class="text-[11px] text-white/50 mt-1">Senin, 2 November 2026</p>
            </div>
            <span class="text-[9px] bg-[#9a0000] text-white px-2 py-1 rounded-full font-bold">LIVE</span>
          </div>
        </div>
        <div class="px-4 pb-4">
          <div class="grid grid-cols-7 gap-1 text-[9px] text-zinc-400 text-center mb-1 font-bold"><span>MG</span><span>SN</span><span>SL</span><span>RB</span><span>KM</span><span>JM</span><span>SB</span></div>
          <div id="calGrid" class="grid grid-cols-7 gap-1"></div>
        </div>
      </div>
    </div>

    <div class="glass-card rounded-[22px] p-5 lg:p-6 flex-1">
      <div class="flex items-center justify-between"><h3 class="font-bold text-[14px]">Menu Alumni</h3><span class="bg-zinc-100 text-zinc-600 text-[10px] font-bold px-3 py-1.5 rounded-full">View Only</span></div>
      <p class="text-[11px] text-zinc-400 mt-1">Akses lihat saja - OSI</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-5">
        @php $openIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>'; @endphp
        @if($kelolaOn)
        <a href="{{ route('alumni.anggota') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-zinc-900 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="3"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">Lihat Anggota</h4><p class="text-[10px] text-zinc-500 truncate">{{ $totalAll }} Anggota • PPS, PPL, Capas, Alumni</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-zinc-900 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>
        @else
        <div class="card-inner rounded-[14px] p-4 flex justify-between items-center opacity-60 cursor-not-allowed"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-zinc-400 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="3"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px] flex items-center gap-1.5">Lihat Anggota <span class="text-zinc-400">{!! $lockIcon !!}</span></h4><p class="text-[10px] text-zinc-500 truncate">Terkunci oleh Developer</p></div></div><span class="text-[9px] bg-zinc-100 px-2 py-1 rounded-full font-bold flex items-center gap-1">{!! $lockIcon !!} LOCKED</span></div>
        @endif

        {{-- E-KAS CARD - BARU --}}
        @if($ekasOn)
        <a href="{{ $ekasUrl }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-emerald-600 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">E-KAS</h4><p class="text-[10px] text-zinc-500 truncate">Lihat Kas • View Only • Alumni</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>
        @else
        <div class="card-inner rounded-[14px] p-4 flex justify-between items-center opacity-60 cursor-not-allowed"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-zinc-400 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px] flex items-center gap-1.5">E-KAS <span class="text-zinc-400">{!! $lockIcon !!}</span></h4><p class="text-[10px] text-zinc-500 truncate">Terkunci oleh Developer</p></div></div><span class="text-[9px] bg-zinc-100 px-2 py-1 rounded-full font-bold flex items-center gap-1">{!! $lockIcon !!} LOCKED</span></div>
        @endif

        <div class="card-inner rounded-[14px] p-4 flex justify-between items-center opacity-60"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-[#9a0000] text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">ID Card Alumni</h4><p class="text-[10px] text-zinc-500 truncate">{{ Str::limit($namaFull,22) }} • View Only</p></div></div><span class="text-[9px] bg-zinc-100 px-2 py-1 rounded-full font-bold">SOON</span></div>
      </div>
      <p class="text-[10px] text-zinc-400 mt-5">* Alumni tidak bisa mengubah data. Kalo ada data yang salah, hubungi Developer</p>
    </div>
    <div class="mt-auto pt-10 pb-2 text-center"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
  </div>
</div>
<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');
const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');document.body.style.overflow='hidden';}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');document.body.style.overflow='';}
openBtn?.addEventListener('click',openMenu);closeBtn?.addEventListener('click',closeMenu);overlay?.addEventListener('click',closeMenu);

let viewDate = new Date();
function getWIB(){ return new Date(new Date().toLocaleString('en-US',{timeZone:'Asia/Jakarta'})); }
function renderCalendar(){
  const grid=document.getElementById('calGrid'); const title=document.getElementById('calTitle');
  if(!grid) return;
  grid.innerHTML='';
  const y=viewDate.getFullYear(), m=viewDate.getMonth();
  title.textContent=viewDate.toLocaleDateString('id-ID',{month:'long', year:'numeric', timeZone:'Asia/Jakarta'}).toUpperCase();
  const first=new Date(y,m,1).getDay(); const days=new Date(y,m+1,0).getDate(); const prevDays=new Date(y,m,0).getDate();
  for(let i=0;i<first;i++){const el=document.createElement('div');el.className='cal-day text-zinc-300';el.textContent=prevDays-first+1+i;grid.appendChild(el);}
  const today=getWIB();
  for(let d=1; d<=days; d++){const el=document.createElement('div'); const isToday=d===today.getDate()&&m===today.getMonth()&&y===today.getFullYear(); el.className='cal-day '+(isToday?'today':'text-zinc-700 font-bold'); el.textContent=d; grid.appendChild(el);}
}
function tick(){
  const now=getWIB();
  const h=String(now.getHours()).padStart(2,'0'), mm=String(now.getMinutes()).padStart(2,'0'), s=String(now.getSeconds()).padStart(2,'0');
  const t=document.getElementById('gshockTime'); if(t) t.innerHTML=`${h}<span class="dot">:</span>${mm}<span class="dot">:</span>${s}`;
  const f=document.getElementById('gshockFull'); if(f) f.textContent=now.toLocaleDateString('id-ID',{weekday:'long', day:'numeric', month:'long', year:'numeric', timeZone:'Asia/Jakarta'});
}
document.getElementById('prevMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()-1);renderCalendar();});
document.getElementById('nextMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()+1);renderCalendar();});
renderCalendar(); tick(); setInterval(tick,1000);
</script>
</body>
</html>