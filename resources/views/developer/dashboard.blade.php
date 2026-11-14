<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Developer Dashboard - OSI</title>
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
#toast{transition:all .3s ease; transform:translateY(20px); opacity:0; pointer-events:none}
#toast.show{transform:translateY(0); opacity:1}
@keyframes notifShrink { from { transform: scaleX(1); } to { transform: scaleX(0); } }
@keyframes notifOut { to { opacity: 0; transform: translateY(-6px); } }
#notifDev.hide { animation: notifOut .3s ease forwards; }
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? auth()->user()->name ?? auth()->user()->username ?? 'Developer';
$namaFullUpper = Str::upper($namaFull);
$namaFirstShort = Str::limit($namaFullUpper, 12, '');
$angkatan = $angkatanFix ?? auth()->user()->angkatan_masuk ?? 'All';
$totalCapas = $jumlahCapas ?? $totalCapas ?? 0;
$totalPpl = $jumlahPpl ?? $totalPpl ?? 0;
$totalPps = $jumlahPps ?? $totalPps ?? 0;
$totalAlumni = $jumlahAlumni ?? $totalAlumni ?? 0;
$totalDev = $jumlahDeveloper ?? $totalDeveloper ?? $totalDev ?? 0;
$totalAll = $totalAnggota ?? ($totalCapas + $totalPpl + $totalPps + $totalAlumni + $totalDev);
@endphp

<div class="lg:hidden flex justify-between items-center p-4 sticky top-0 z-20 bg-[#7a0000] border-b border-white/10">
  <div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-8 h-8 object-contain"><span class="font-[800] italic text-white text-[14px]">OSI</span></div>
  <button id="btnOpen" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg></button>
</div>
<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">
  <div id="sidebar" class="w-[85%] max-w-[280px] lg:w-[280px] bg-[#4a0000] lg:bg-black/30 backdrop-blur-xl border-r border-white/10 fixed inset-y-0 left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col h-[100dvh] lg:h-screen">
    <div class="p-7 flex-1 overflow-y-auto no-scrollbar">
      <div class="flex items-center justify-between"><div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-10 h-10 object-contain drop-shadow"><div><h1 class="font-[800] text-white text-[16px] italic leading-none">OSI</h1><p class="text-[10px] font-bold tracking-[0.2em] text-white/60 mt-1">BY PASKIBRA NESTA</p></div></div><button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
      <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">MAIN MENU</p>
        <div class="space-y-1.5">
          <a href="{{ route('dev.dashboard') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
          <a href="{{ route('dev.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Kelola Anggota</a>
          <a href="{{ route('dev.validasiKas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>E-KAS</a>
          <a href="#" class="flex items-center gap-3 text-white/60 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Absensi</a>
          <a href="#" class="flex items-center gap-3 text-white/60 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>E-Inventaris</a>
          <a href="#" class="flex items-center gap-3 text-white/60 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Materi</a>
        </div>
      </div>
      <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
        <p class="text-[10px] font-bold tracking-widest text-amber-300">DEVELOPER • {{ $angkatan }}</p>
        <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Full akses. Bisa tambah, edit, hapus, pindah angkatan & atur fitur.</p>
      </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0"><div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10"><div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr(auth()->user()->username,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ $namaFirstShort }}</p><p class="text-white/40 text-[9px] mt-1">Developer • {{ $angkatan }}</p></div></div><form method="POST" action="{{ route('logout') }}">@csrf<button class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form></div></div>
  </div>

  <div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen">
    <div class="mb-4">
      <p class="text-white text-[18px] lg:text-[22px] font-[800]">Hi, {{ $namaFullUpper }}</p>
      <p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase">Developer • {{ $angkatan }} • {{ $namaFull }}</p>
    </div>

    @if(session('success') || session('error'))
    @php $notifMsg = session('success') ?? session('error'); $isError = session('error'); @endphp
    <div id="notifDev" class="relative overflow-hidden rounded-[14px] px-4 py-3 mb-4 {{ $isError ? 'bg-[#FFF1F1] border border-red-100' : 'bg-emerald-50 border border-emerald-100' }}">
      <div class="flex items-center gap-2.5"><p class="text-[12px] font-bold {{ $isError ? 'text-[#7a0000]' : 'text-emerald-800' }}">{{ $notifMsg }}</p><button type="button" onclick="document.getElementById('notifDev').remove()" class="ml-auto text-zinc-400 leading-none flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>
      <div class="absolute bottom-0 left-0 h-[3px] {{ $isError ? 'bg-[#9a0000]' : 'bg-emerald-600' }} w-full origin-left" style="animation: notifShrink 5s linear forwards"></div>
    </div>
    <script>setTimeout(()=>{const n=document.getElementById('notifDev'); if(n){n.classList.add('hide'); setTimeout(()=>n.remove(),300)}},5000)</script>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalCapas }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPL</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalPpl }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalPps }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">ALUMNI</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalAlumni }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">DEVELOPER</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $totalDev }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.7fr_0.9fr] gap-4 mb-4">
      <div class="glass-card rounded-[22px] p-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div><p class="text-[10px] font-bold tracking-widest text-zinc-400">TOTAL ANGGOTA</p><p class="text-[36px] font-[800] leading-none mt-2">{{ $totalAll }}</p><p class="text-[11px] text-zinc-500 mt-2">Terdata</p></div>
          <div><p class="text-[10px] font-bold tracking-widest text-zinc-400">E-KAS PENDING</p><p class="text-[36px] font-[800] leading-none mt-2">{{ $pendingKas ?? 0 }}</p><p class="text-[11px] text-zinc-500 mt-2">Menunggu</p></div>
          <div><p class="text-[10px] font-bold tracking-widest text-zinc-400">SALDO TERKINI</p><p class="text-[20px] font-[800] leading-none mt-3">Rp {{ number_format($saldo ?? 0,0,',','.') }}</p><p class="text-[11px] text-zinc-500 mt-2">Total kas</p></div>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-2.5 mt-5">
          <div class="rounded-[14px] bg-white border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[20px] font-[800] mt-1 text-zinc-800">{{ $totalCapas }}</p><span class="inline-flex mt-1 text-[9px] bg-[#9a0000] text-white px-2 py-0.5 rounded-full font-bold">{{ $angkatanCapas ?? 'ANGKATAN 2026' }}</span></div>
          <div class="rounded-[14px] bg-red-50 border border-red-100 p-3"><p class="text-[9px] font-bold tracking-widest text-[#9a0000]">PPL</p><p class="text-[20px] font-[800] mt-1 text-[#9a0000]">{{ $totalPpl }}</p><span class="inline-flex mt-1 text-[9px] bg-zinc-900 text-white px-2 py-0.5 rounded-full font-bold">{{ $angkatanPpl ?? 'ANGKATAN 2025' }}</span></div>
          <div class="rounded-[14px] bg-emerald-50 border border-emerald-100 p-3"><p class="text-[9px] font-bold tracking-widest text-emerald-600">PPS</p><p class="text-[20px] font-[800] mt-1 text-emerald-700">{{ $totalPps }}</p><span class="inline-flex mt-1 text-[9px] bg-zinc-900 text-white px-2 py-0.5 rounded-full font-bold">{{ $angkatanPps ?? 'ANGKATAN 2024' }}</span></div>
          <div class="rounded-[14px] bg-zinc-50 border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-500">ALUMNI</p><p class="text-[20px] font-[800] mt-1 text-zinc-800">{{ $totalAlumni }}</p><span class="inline-flex mt-1 text-[9px] bg-zinc-200 text-zinc-700 px-2 py-0.5 rounded-full font-bold">{{ $angkatanAlumni ?? 'SEMUA ANGKATAN' }}</span></div>
          <div class="rounded-[14px] bg-zinc-900 border border-zinc-900 p-3"><p class="text-[9px] font-bold tracking-widest text-white/60">DEVELOPER</p><p class="text-[20px] font-[800] mt-1 text-white">{{ $totalDev }}</p><span class="inline-flex mt-1 text-[9px] bg-[#9a0000] text-white px-2 py-0.5 rounded-full font-bold">FULL AKSES</span></div>
        </div>
        <div class="mt-4 rounded-[14px] bg-zinc-900 text-white p-4 flex justify-between items-center">
          <div><p class="text-[9px] font-bold tracking-widest text-white/50">STATUS KAMU</p><p class="text-[13px] font-bold mt-1">Developer • Full Akses • {{ $angkatan }}</p></div>
          <span class="text-[9px] bg-[#9a0000] text-white px-3 py-1.5 rounded-full font-bold">FULL AKSES</span>
        </div>
      </div>

      <div class="glass-card rounded-[22px] overflow-hidden flex flex-col">
        <div class="bg-zinc-900 px-5 py-3 flex justify-between items-center">
          <p id="calTitle" class="text-[11px] font-bold tracking-widest text-white/60 uppercase">NOVEMBER 2026</p>
          <div class="flex gap-1"><button id="prevMonth" class="w-6 h-6 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button><button id="nextMonth" class="w-6 h-6 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button></div>
        </div>
        <div class="bg-[#0e0e0e] m-3 rounded-[14px] p-4 border border-zinc-800">
          <div class="flex justify-between items-start">
            <div><p class="text-[9px] tracking-widest text-white/30 font-bold">WAKTU INDONESIA BARAT</p><p id="gshockTime" class="gshock text-[32px] leading-none text-white mt-1">00:00:00</p><p id="gshockFull" class="text-[11px] text-white/50 mt-1">Senin, 2 November 2026</p></div>
            <span class="text-[9px] bg-[#9a0000] text-white px-2 py-1 rounded-full font-bold">LIVE</span>
          </div>
        </div>
        <div class="px-4 pb-4">
          <div class="grid grid-cols-7 gap-1 text-[9px] text-zinc-400 text-center mb-1 font-bold"><span>MG</span><span>SN</span><span>SL</span><span>RB</span><span>KM</span><span>JM</span><span>SB</span></div>
          <div id="calGrid" class="grid grid-cols-7 gap-1"></div>
        </div>
      </div>
    </div>

    <div class="glass-card rounded-[22px] p-5 lg:p-6">
      <div class="flex items-center justify-between"><h3 class="font-bold text-[14px]">Menu Developer - Control Center</h3><span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-3 py-1.5 rounded-full">ON / OFF</span></div>
      <p class="text-[11px] text-zinc-400 mt-1">Pencet card buat masuk ke menunya. Pencet toggle buat lock/unlock fitur buat PPS/PPL/CAPAS.</p>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 mt-5">
        @php
          $openIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>';
          $lockIcon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>';
          $items = [
            ['key'=>'idc','label'=>'IDC','desc'=>'Kartu identitas & download IDC.','bg'=>'bg-[#9a0000]','icon'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg>','href'=> route('dev.anggota') ],
            ['key'=>'kelola_anggota','label'=>'Kelola Anggota','desc'=>'Tambah & atur CAPAS, PPL, PPS.','bg'=>'bg-zinc-900','icon'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="3"/></svg>','href'=> route('dev.anggota') ],
            ['key'=>'e_kas','label'=>'E-KAS','desc'=>'Validasi bukti setor.','bg'=>'bg-emerald-600','icon'=>'<span class="font-[800] text-[11px]">Rp</span>','href'=> route('dev.validasiKas') ],
            ['key'=>'absensi','label'=>'Absensi','desc'=>'Kelola kehadiran.','bg'=>'bg-blue-600','icon'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 4h8a2 2 0 012 2v14a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"/><path d="M9 12l2 2 4-4"/></svg>','href'=> '#'],
            ['key'=>'e_inventaris','label'=>'E-Inventaris','desc'=>'Data inventaris.','bg'=>'bg-amber-500','icon'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>','href'=> '#'],
            ['key'=>'materi','label'=>'Materi','desc'=>'Materi PBB.','bg'=>'bg-violet-600','icon'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>','href'=> '#'],
          ];
        @endphp
        @foreach($items as $it)
        @php $on = (bool)($flags[$it['key']] ?? 1); @endphp
        <div class="card-inner rounded-[14px] p-4 flex justify-between items-center group cursor-pointer {{ $on ? '' : 'opacity-60' }}" data-card="{{ $it['key'] }}" data-href="{{ $it['href'] }}">
          <div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] {{ $it['bg'] }} text-white flex items-center justify-center shrink-0">{!! $it['icon'] !!}</div><div class="min-w-0"><h4 class="font-bold text-[12px] flex items-center gap-1">{{ $it['label'] }} <span class="lock-badge {{ $on ? 'hidden' : '' }} text-red-500">{!! $lockIcon !!}</span></h4><p class="text-[10px] text-zinc-500 truncate desc-text" data-desc="{{ $it['desc'] }}">{{ $on ? $it['desc'] : 'Terkunci' }}</p></div></div>
          <div class="flex items-center gap-2 shrink-0 ml-2" onclick="event.stopPropagation()"><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" data-key="{{ $it['key'] }}" class="sr-only peer toggle-feature" {{ $on ? 'checked' : '' }}><div class="w-9 h-5 bg-zinc-200 rounded-full peer peer-checked:bg-emerald-600 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div></label><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-zinc-900 group-hover:text-white flex items-center justify-center transition-all">{!! $openIcon !!}</div></div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="mt-auto pt-10 pb-2 text-center"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
  </div>
</div>

<div id="toast" class="fixed bottom-5 left-1/2 -translate-x-1/2 z-[100] bg-zinc-900 text-white text-[12px] font-bold px-4 py-2.5 rounded-full shadow-2xl">Disimpan</div>

<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');document.body.style.overflow='hidden';}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');document.body.style.overflow='';}
openBtn?.addEventListener('click',openMenu);closeBtn?.addEventListener('click',closeMenu);overlay?.addEventListener('click',closeMenu);
let viewDate = new Date();
function getWIB(){ return new Date(new Date().toLocaleString('en-US',{timeZone:'Asia/Jakarta'})); }
function renderCalendar(){
  const grid=document.getElementById('calGrid'); const title=document.getElementById('calTitle');
  grid.innerHTML=''; const y=viewDate.getFullYear(), m=viewDate.getMonth();
  title.textContent=viewDate.toLocaleDateString('id-ID',{month:'long', year:'numeric', timeZone:'Asia/Jakarta'}).toUpperCase();
  const first=new Date(y,m,1).getDay(); const days=new Date(y,m+1,0).getDate(); const prevDays=new Date(y,m,0).getDate();
  for(let i=0;i<first;i++){const el=document.createElement('div');el.className='cal-day text-zinc-300';el.textContent=prevDays-first+1+i;grid.appendChild(el);}
  const today=getWIB(); for(let d=1; d<=days; d++){const el=document.createElement('div'); const isToday=d===today.getDate()&&m===today.getMonth()&&y===today.getFullYear(); el.className='cal-day '+(isToday?'today':'text-zinc-700 font-bold'); el.textContent=d; grid.appendChild(el);}
}
function tick(){
  const now=getWIB(); const h=String(now.getHours()).padStart(2,'0'), mm=String(now.getMinutes()).padStart(2,'0'), s=String(now.getSeconds()).padStart(2,'0');
  document.getElementById('gshockTime').innerHTML=`${h}<span class="dot">:</span>${mm}<span class="dot">:</span>${s}`;
  document.getElementById('gshockFull').textContent=now.toLocaleDateString('id-ID',{weekday:'long', day:'numeric', month:'long', year:'numeric', timeZone:'Asia/Jakarta'});
}
document.getElementById('prevMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()-1);renderCalendar();});
document.getElementById('nextMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()+1);renderCalendar();});
renderCalendar(); tick(); setInterval(tick,1000);

let toastTimer;
function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(()=> t.classList.remove('show'), 2000);
}
// Klik card = bypass masuk ke menu, klik toggle = lock/unlock doang
document.querySelectorAll('[data-card]').forEach(card=>{
  card.addEventListener('click', ()=>{
    const href = card.dataset.href;
    if(href && href !== '#') window.location.href = href;
  });
});
document.querySelectorAll('.toggle-feature').forEach(el=>{
  el.addEventListener('change', async function(){
    const key = this.dataset.key;
    const enabled = this.checked;
    const card = document.querySelector('[data-card="'+key+'"]');
    const prev = !enabled;
    this.disabled = true;
    if(card){
      card.classList.toggle('opacity-60', !enabled);
      const lock = card.querySelector('.lock-badge');
      if(lock) lock.classList.toggle('hidden', enabled);
      const desc = card.querySelector('.desc-text');
      if(desc) desc.textContent = enabled ? desc.dataset.desc : 'Terkunci';
    }
    try{
      const res = await fetch("{{ route('dev.features.toggle') }}", {
        method:'POST',
        headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}",'Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify({key:key, enabled:enabled})
      });
      const data = await res.json().catch(()=>({}));
      if(!res.ok || data.success === false) throw new Error(data.message || 'Gagal');
      showToast(enabled ? 'Fitur diaktifkan' : 'Fitur dikunci');
    }catch(e){
      this.checked = prev;
      if(card){
        card.classList.toggle('opacity-60', !prev);
        const lock = card.querySelector('.lock-badge');
        if(lock) lock.classList.toggle('hidden', prev);
        const desc = card.querySelector('.desc-text');
        if(desc) desc.textContent = prev ? desc.dataset.desc : 'Terkunci';
      }
      showToast('Gagal simpan, coba lagi');
    } finally {
      this.disabled = false;
    }
  });
});
</script>
</body>
</html>