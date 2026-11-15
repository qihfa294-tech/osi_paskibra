<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>CAPAS Dashboard - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=JetBrains+Mono:wght@700;800&family=Playfair+Display:wght@600;700;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; -webkit-font-smoothing:antialiased}
.bg-paskibra{background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border:1px solid rgba(255,255,255,0.2);}
.card-inner{background:white; border:1px solid #f0f0f0; transition:all .22s ease; transform:translateZ(0)}
.card-inner:hover{transform:translateY(-4px) translateZ(0); box-shadow:0 14px 32px rgba(0,0,0,.13)}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
.gshock{font-family:'JetBrains Mono', monospace; font-weight:800; letter-spacing:0.06em; font-variant-numeric: tabular-nums;}
.dot{animation: blink 1s steps(1) infinite}
@keyframes blink{0%,50%{opacity:1}51%,100%{opacity:0.2}}
.cal-day{width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:9px;border-radius:9999px}
.cal-day.today{background:#9a0000;color:white;font-weight:800}
.bio-input{width:100%;padding:11px 12px;border:1px solid #e5e5e5;border-radius:12px;font-size:13px;background:#fafafa;outline:none;transition:all .2s}
.bio-input:focus{background:white;border-color:#9a0000;box-shadow:0 0 0 3px rgba(154,0,0,.12)}
.bio-label{font-size:10px;font-weight:800;letter-spacing:.12em;color:#71717a;text-transform:uppercase}
.cropper-view-box, .cropper-face{border-radius:50% !important;}
.cropper-view-box{outline:2px solid #9a0000; outline-offset:0;}
.cropper-line, .cropper-point{display:none !important;}
.crop-btn{width:40px;height:40px;border-radius:9999px;background:#f4f4f5;border:1px solid #e5e5e5;display:flex;align-items:center;justify-content:center;color:#18181b;cursor:pointer;transition:all .15s}
.crop-btn:hover{background:#9a0000;color:#fff;border-color:#9a0000}
.crop-btn:active{transform:scale(.94)}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes popIn{0%{transform:scale(.85) translateY(20px);opacity:0}100%{transform:scale(1) translateY(0);opacity:1}}
@keyframes shine{0%{transform:translateX(-100%)}100%{transform:translateX(200%)}}
.birthday-pop{animation:popIn .5s cubic-bezier(.2,1.4,.4,1) both}
.birthday-float{animation:floatY 3s ease-in-out infinite}
.birthday-shine{position:relative;overflow:hidden}
.birthday-shine::after{content:'';position:absolute;top:0;left:0;width:40%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.35),transparent);animation:shine 2.2s ease-in-out infinite}
@keyframes mailboxFloat{0%,100%{transform:translateY(0) rotate(-1deg)}50%{transform:translateY(-10px) rotate(1deg)}}
@keyframes letterUp{0%{transform:translateY(60%) scale(.9);opacity:0}100%{transform:translateY(0) scale(1);opacity:1}}
@keyframes sealPop{0%{transform:scale(0)}70%{transform:scale(1.2)}100%{transform:scale(1)}}
.mailbox-float{animation:mailboxFloat 2.8s ease-in-out infinite}
.letter-open{animation:letterUp .7s cubic-bezier(.2,1.2,.4,1) both}
.seal-pop{animation:sealPop .5s cubic-bezier(.2,1.4,.4,1) both}
.envelope-flap{transition:transform .6s ease, opacity .4s ease;transform-origin:top center;backface-visibility:hidden}
.envelope-open .envelope-flap{transform:rotateX(180deg);opacity:0}
.letter-paper{font-family:'Libre Baskerville',Georgia,serif}
.letter-title{font-family:'Playfair Display',Georgia,serif}
canvas.confetti-canvas{z-index:20000 !important; position:fixed !important; inset:0 !important; pointer-events:none !important;}
.paper-texture{background-image: radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px); background-size: 4px 4px;}
.envelope-paper{background: linear-gradient(145deg, #f7ecd2 0%, #efe0bd 55%, #e8d5a8 100%);}
.envelope-pocket{background: linear-gradient(180deg, #e9d8b0 0%, #dfcda2 100%);}
.envelope-flap-bg{background: linear-gradient(180deg, #e2cfa0 0%, #d9c493 100%);}
.wax-seal{background: radial-gradient(circle at 35% 28%, #d40000 0%, #9a0000 45%, #5e0000 100%); box-shadow: 0 6px 14px rgba(122,0,0,.45), inset 0 2px 3px rgba(255,255,255,.35), inset 0 -3px 6px rgba(0,0,0,.35);}
.wax-edge{border-radius: 48% 52% 50% 50% / 52% 48% 52% 48%;}
.gold-line{height:1px; background: linear-gradient(90deg, transparent, #c9a86a, transparent);}
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
use Carbon\Carbon;
$userLogin = auth()->user();
$namaFull = $namaLengkap ?? $userLogin->nama_lengkap ?? $userLogin->name ?? $userLogin->username ?? 'CAPAS';
$namaFullUpper = Str::upper($namaFull);
$angkatan = $angkatanFix ?? '2023';
$totalAll = ($totalPps ?? 0) + ($totalPpl ?? 0) + ($totalCapasSeangkatan ?? $totalCapas ?? $totalAnggota ?? 0);
$foto = $userLogin->getRawOriginal('foto') ?? $userLogin->anggota?->foto ?? ($anggota->foto ?? null);
$fotoUrl = $foto ? asset('storage/' . $foto) : null;
$showBiodata = !empty($perluLengkapiBiodata) && $perluLengkapiBiodata;
$tglLahirRaw = $anggota->tanggal_lahir ?? $userLogin->tanggal_lahir ?? null;
$umur = null; $isBirthday = false;
if($tglLahirRaw){
  try{
    $tglLahir = Carbon::parse($tglLahirRaw);
    $umur = $tglLahir->age;
    $todayWIB = Carbon::now('Asia/Jakarta');
    $isBirthday = $tglLahir->format('m-d') === $todayWIB->format('m-d');
  }catch(\Exception $e){}
}
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
    <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">CAPAS MENU</p>
        <div class="space-y-1.5">
        <a href="{{ route('capas.dashboard') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
        @if(isFeatureEnabled('idc'))<a href="{{ route('capas.idc') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg>IDC Saya</a>@endif
        @if(isFeatureEnabled('kelola_anggota'))<a href="{{ route('capas.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Anggota</a>@endif
        @if(isFeatureEnabled('e_kas'))<a href="{{ route('capas.kas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>E-KAS</a>@endif
        @if(isFeatureEnabled('absensi'))<a href="{{ route('capas.absensi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Absensi</a>@endif
        @if(isFeatureEnabled('materi'))<a href="{{ route('capas.materi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Materi</a>@endif
        </div>
    </div>
    <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
      <p class="text-[10px] font-bold tracking-widest text-amber-300">CAPAS • {{ $angkatan }}</p>
      <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Anda hanya dapat melihat jabatan Anda sendiri. Jabatan anggota lain tidak ditampilkan. Anda tidak dapat menambah, mengubah, atau menghapus data.</p>
    </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0"><div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10"><div class="flex items-center gap-3 min-w-0">
    <button type="button" onclick="bukaEditProfil()" title="Edit Profil" class="shrink-0 cursor-pointer">
    @if($fotoUrl)
        <img src="{{ $fotoUrl }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-white/20">
    @else
        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div>
    @endif
    </button>
    <button type="button" onclick="bukaEditProfil()" class="min-w-0 text-left cursor-pointer">
      <p class="text-white text-[11px] font-semibold truncate">{{ Str::limit($namaFull, 18) }}</p><p class="text-white/40 text-[9px] mt-1">CAPAS • {{ $angkatan }} • Edit Profil</p>
    </button>
    </div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></button></form></div></div>
</div>

<div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen">
    <div class="mb-4"><p class="text-white text-[18px] lg:text-[22px] font-[800] leading-tight truncate">Selamat Datang, {{ $namaFullUpper }} (CAPAS)</p><p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase truncate">CAPAS • ANGKATAN {{ $angkatan }} • {{ $namaFull }}</p></div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-4 mb-4 items-start">
        <div class="glass-card rounded-[20px] p-4">
            <div class="flex justify-between items-start"><div><p class="text-[9px] font-bold tracking-widest text-zinc-400">TOTAL ANGGOTA</p><p class="text-[28px] font-[800] leading-none mt-1.5">{{ $totalAnggota ?? $totalAll }}</p><p class="text-[10px] text-zinc-500 mt-1.5">{{ $totalPps ?? 0 }} PPS • {{ $totalCapasSeangkatan ?? $totalCapas ?? 0 }} Capas Seangkatan</p></div><div class="w-9 h-9 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-500 shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></div></div>
            <div class="grid grid-cols-3 gap-2.5 mt-4">
                <div class="rounded-[12px] bg-emerald-50 border border-emerald-100 p-3"><p class="text-[9px] font-bold tracking-widest text-emerald-600">PPS</p><p class="text-[18px] font-[800] mt-1 leading-none text-emerald-700">{{ $totalPps ?? 0 }}</p><p class="text-[10px] text-emerald-600/70 mt-1"></p></div>
                <div class="rounded-[12px] bg-red-50 border border-red-100 p-3"><p class="text-[9px] font-bold tracking-widest text-[#9a0000]">PPL</p><p class="text-[18px] font-[800] mt-1 leading-none text-[#9a0000]">{{ $totalPpl ?? 0 }}</p><p class="text-[10px] text-[#9a0000]/60 mt-1"></p></div>
                <div class="rounded-[12px] bg-white border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[18px] font-[800] mt-1 leading-none text-zinc-800">{{ $totalCapasSeangkatan ?? $totalCapas ?? 0 }}</p><p class="text-[10px] text-zinc-500 mt-1"></p></div>
            </div>
            <div class="grid grid-cols-2 gap-2.5 mt-2.5">
                <div class="rounded-[12px] bg-zinc-50 border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-400">STATUS KAS ANDA</p><p class="text-[16px] font-[800] mt-1 leading-none {{ ($statusKas ?? '') == 'Lunas' ? 'text-emerald-600' : 'text-[#9a0000]' }}">{{ $statusKas ?? 'Belum Ada Data' }}</p><p class="text-[10px] text-zinc-500 mt-1">Bulan {{ now()->format('F Y') }}</p></div>
                <div class="rounded-[12px] bg-zinc-50 border border-zinc-200 p-3"><p class="text-[9px] font-bold tracking-widest text-zinc-400">ABSENSI ANDA</p><p class="text-[16px] font-[800] mt-1 leading-none">{{ $persenAbsen ?? 0 }}%</p><div class="w-full bg-zinc-200 rounded-full h-1.5 mt-2"><div class="bg-[#9a0000] h-1.5 rounded-full" style="width: {{ $persenAbsen ?? 0 }}%"></div></div></div>
            </div>
        </div>

        <div class="glass-card rounded-[20px] overflow-hidden flex flex-col">
            <div class="bg-zinc-900 px-4 py-2.5 flex justify-between items-center">
                <p id="calTitle" class="text-[10px] font-bold tracking-widest text-white/60 uppercase">NOVEMBER 2026</p>
                <div class="flex gap-1">
                    <button id="prevMonth" class="w-5 h-5 rounded-full bg-white/10 text-white flex items-center justify-center text-[10px]">‹</button>
                    <button id="nextMonth" class="w-5 h-5 rounded-full bg-white/10 text-white flex items-center justify-center text-[10px]">›</button>
                </div>
            </div>
            <div class="bg-[#0e0e0e] m-2.5 rounded-[12px] p-3 border border-zinc-800">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[8px] tracking-widest text-white/30 font-bold">WAKTU INDONESIA BARAT</p>
                        <p id="gshockTime" class="gshock text-[22px] leading-none text-white mt-1">00:00:00</p>
                        <p id="gshockFull" class="text-[10px] text-white/50 mt-1">Senin, 2 November 2026</p>
                    </div>
                    <span class="text-[9px] bg-[#9a0000] text-white px-2 py-1 rounded-full font-bold">LIVE</span>
                </div>
            </div>
            <div class="px-3 pb-3">
                <div class="grid grid-cols-7 gap-0 text-[8px] text-zinc-400 text-center mb-1 font-bold"><span>MG</span><span>SN</span><span>SL</span><span>RB</span><span>KM</span><span>JM</span><span>SB</span></div>
                <div id="calGrid" class="grid grid-cols-7 gap-0.5"></div>
            </div>
        </div>
    </div>

    <div class="glass-card rounded-[22px] p-5 lg:p-7 flex-1">
    <h3 class="font-bold text-[14px]">Menu CAPAS</h3><p class="text-[11px] text-zinc-400 mt-1">Akses Cepat CAPAS OSI</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-5">
        @php $openIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>'; @endphp
        @if(isFeatureEnabled('idc'))<a href="{{ route('capas.idc') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-[#9a0000] text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">IDC Saya (CAPAS)</h4><p class="text-[10px] text-zinc-500 truncate">{{ Str::limit($namaFull,22) }}</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-[#9a0000] group-hover:text-white group-hover:border-[#9a0000] flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>@endif
        @if(isFeatureEnabled('kelola_anggota'))<a href="{{ route('capas.anggota') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-zinc-900 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="3"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">Anggota</h4><p class="text-[10px] text-zinc-500 truncate">{{ $totalCapasSeangkatan ?? $totalCapas ?? 0 }} Anggota Seangkatan</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-zinc-900 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>@endif
        @if(isFeatureEnabled('e_kas'))<a href="{{ route('capas.kas') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-emerald-600 text-white flex items-center justify-center font-[800] text-[11px] shrink-0">Rp</div><div class="min-w-0"><h4 class="font-bold text-[12px]">E-KAS</h4><p class="text-[10px] text-zinc-500 truncate">Status: {{ $statusKas ?? 'Belum Ada Data' }}</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>@endif
        @if(isFeatureEnabled('absensi'))<a href="{{ route('capas.absensi') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-blue-600 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 4h8a2 2 0 012 2v14a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"/><path d="M9 12l2 2 4-4"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">Absensi</h4><p class="text-[10px] text-zinc-500 truncate">Kehadiran Anda: {{ $persenAbsen ?? 0 }}%</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>@endif
        @if(isFeatureEnabled('materi'))<a href="{{ route('capas.materi') }}" class="card-inner rounded-[14px] p-4 flex justify-between items-center group"><div class="flex items-center gap-2.5 min-w-0"><div class="w-8 h-8 rounded-[8px] bg-violet-600 text-white flex items-center justify-center shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg></div><div class="min-w-0"><h4 class="font-bold text-[12px]">Materi Paskibra</h4><p class="text-[10px] text-zinc-500 truncate">Pelajari Materi Paskibra.</p></div></div><div class="w-7 h-7 rounded-full border border-zinc-200 group-hover:bg-violet-600 group-hover:text-white flex items-center justify-center transition-all shrink-0 ml-2">{!! $openIcon !!}</div></a>@endif
    </div>
    </div>
    <div class="mt-6 text-center pb-2"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
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
  document.getElementById('gshockTime').innerHTML=`${h}<span class="dot">:</span>${mm}<span class="dot">:</span>${s}`;
  document.getElementById('gshockFull').textContent=now.toLocaleDateString('id-ID',{weekday:'long', day:'numeric', month:'long', year:'numeric', timeZone:'Asia/Jakarta'});
}
document.getElementById('prevMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()-1);renderCalendar();});
document.getElementById('nextMonth')?.addEventListener('click',()=>{viewDate.setMonth(viewDate.getMonth()+1);renderCalendar();});
renderCalendar(); tick(); setInterval(tick,1000);
</script>

{{-- MODAL BIODATA / EDIT PROFIL --}}
<div id="modalBiodata" class="{{ $showBiodata ? '' : 'hidden' }} fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
  <div class="w-full max-w-[540px] max-h-[92vh] flex flex-col rounded-[22px] overflow-hidden shadow-2xl bg-white">
    <div class="px-6 pt-5 pb-6 shrink-0 relative" style="background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);">
      @if(!$showBiodata)
      <button type="button" onclick="tutupEditProfil()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center font-bold">X</button>
      @endif
      <div class="flex items-center gap-3">
        <img src="{{ asset('logo_osi.png') }}" class="w-10 h-10 object-contain drop-shadow">
        <div>
          <p class="text-[10px] font-bold tracking-[0.25em] text-white/60">OSI • BY PASKIBRA NESTA</p>
          <h3 class="text-white font-[800] text-[18px] leading-tight mt-0.5">{{ $showBiodata ? 'Lengkapi Biodata Anda' : 'Edit Profil Anda' }}</h3>
        </div>
      </div>
      <p class="text-white/70 text-[12px] mt-3 leading-relaxed">{{ $showBiodata ? 'Silakan lengkapi biodata Anda untuk melanjutkan ke dashboard.' : 'Ubah biodata / foto profil Anda, lalu simpan.' }}</p>
    </div>
    <div class="overflow-y-auto no-scrollbar p-5 lg:p-6 bg-white">
      @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-[#9a0000] text-[12px] font-semibold px-4 py-3 rounded-[12px] mb-4">{{ $errors->first() }}</div>
      @endif
      <form id="formBiodata" action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
        @csrf
        <div id="fotoPreviewBlock" class="flex flex-col items-center text-center pt-1">
          <div class="relative">
            <div class="w-[128px] h-[128px] rounded-full overflow-hidden border-[4px] border-[#9a0000] bg-zinc-100 flex items-center justify-center shadow-lg">
              <img id="previewBulat" src="{{ $fotoUrl ?? '' }}" class="w-full h-full object-cover {{ $fotoUrl ? '' : 'hidden' }}" alt="Preview">
              <span id="previewPlaceholder" class="text-[11px] font-bold text-zinc-400 leading-tight px-3 {{ $fotoUrl ? 'hidden' : '' }}">FOTO<br>PROFIL</span>
            </div>
            <label class="absolute -bottom-1 -right-1 w-9 h-9 rounded-full bg-[#9a0000] text-white flex items-center justify-center cursor-pointer shadow border-2 border-white" title="Pilih Foto">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
              <input id="inputFoto" type="file" name="foto" accept="image/*" @if(!$fotoUrl) required @endif class="hidden">
            </label>
          </div>
          <p class="text-[11px] font-bold text-zinc-800 mt-3">Foto Formal</p>
          <p class="text-[10px] text-zinc-400 leading-relaxed">Gunakan Seragam Sekolah Putih Lengkap Dengan Atribut dan Dasi.</p>
        </div>
        <div id="cropWrap" class="hidden border border-zinc-200 rounded-[14px] overflow-hidden bg-zinc-900">
          <div class="h-[280px]"><img id="imgCrop" class="max-w-full"></div>
          <div class="bg-white p-3">
            <div class="flex items-center justify-center gap-2">
              <button type="button" id="btnPutarKiri" class="crop-btn" title="Putar Kiri"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg></button>
              <button type="button" id="btnPutarKanan" class="crop-btn" title="Putar Kanan"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg></button>
              <button type="button" id="btnZoomOut" class="crop-btn" title="Perkecil"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg></button>
              <button type="button" id="btnZoomIn" class="crop-btn" title="Perbesar"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg></button>
              <button type="button" id="btnReset" class="crop-btn" title="Atur Ulang"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg></button>
            </div>
            <div class="flex gap-2 mt-3">
              <button type="button" id="btnBatalCrop" class="flex-1 py-2.5 rounded-[10px] border border-zinc-200 text-[12px] font-bold">Batal</button>
              <button type="button" id="btnSelesaiCrop" class="flex-1 py-2.5 rounded-[10px] bg-[#9a0000] text-white text-[12px] font-bold">Selesai</button>
            </div>
          </div>
        </div>
        <div>
          <label class="bio-label">Nama Lengkap *</label>
          <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap ?? $anggota->nama ?? $namaLengkap) }}" required class="bio-input mt-1.5 font-bold">
        </div>
        <div>
          <label class="bio-label">Nama Panggilan *</label>
          <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $anggota->nama_panggilan ?? '') }}" required placeholder="Huruf Kapital" class="bio-input mt-1.5">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="bio-label">Jurusan *</label>
            <select id="selectJurusan" name="jurusan" required class="bio-input mt-1.5">
              <option value="">Pilih Jurusan</option>
              @foreach(['MP','BR','AK','DKV','TKJ','TKL'] as $j)
                <option value="{{ $j }}" {{ (old('jurusan', $anggota->jurusan ?? '')==$j)?'selected':'' }}>{{ $j }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="bio-label">Kelas *</label>
            <select id="selectKelas" name="kelas" required class="bio-input mt-1.5">
              <option value="">Pilih Jurusan Terlebih Dahulu</option>
            </select>
          </div>
        </div>
        <div>
          <label class="bio-label">Alamat *</label>
          <textarea name="alamat" required rows="2" class="bio-input mt-1.5 resize-none" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="bio-label">Tempat Lahir *</label>
            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $anggota->tempat_lahir ?? '') }}" required class="bio-input mt-1.5">
          </div>
          <div>
            <label class="bio-label">Tanggal Lahir *</label>
            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir ?? '') }}" required class="bio-input mt-1.5">
          </div>
        </div>
        <div>
          <label class="bio-label">No HP Aktif *</label>
          <input type="text" name="no_hp" value="{{ old('no_hp', $anggota->no_hp ?? '') }}" required placeholder="08xxxxxxxxxx" class="bio-input mt-1.5">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="bio-label">Agama *</label>
            <select name="agama" required class="bio-input mt-1.5">
              <option value="">Pilih Agama</option>
              @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag)
                <option value="{{ $ag }}" {{ ($anggota->agama ?? '')==$ag?'selected':'' }}>{{ $ag }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="bio-label">Jenis Kelamin *</label>
            <select name="jenis_kelamin" required class="bio-input mt-1.5">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="Laki-laki" {{ ($anggota->jenis_kelamin ?? '')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
              <option value="Perempuan" {{ ($anggota->jenis_kelamin ?? '')=='Perempuan'?'selected':'' }}>Perempuan</option>
            </select>
          </div>
        </div>
        <button type="submit" class="w-full bg-[#9a0000] hover:bg-[#7a0000] transition text-white font-[800] text-[13px] py-3.5 rounded-[14px] shadow-lg shadow-red-900/20">{{ $showBiodata ? 'Simpan Biodata' : 'Simpan Perubahan' }}</button>
        @if(!$showBiodata)
        <button type="button" onclick="tutupEditProfil()" class="w-full border border-zinc-200 text-[13px] font-bold py-3 rounded-[14px]">Batal</button>
        @endif
        <p class="text-center text-[10px] tracking-[0.2em] text-zinc-400">PASKIBRA NESTA © 2026</p>
      </form>
    </div>
  </div>
</div>

{{-- POPUP ULANG TAHUN - MAILBOX PREMIUM --}}
@if($isBirthday && !$showBiodata)
<div id="modalUltah" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
  {{-- AMPLOP --}}
  <div id="mailboxClosed" class="w-full max-w-[410px] text-center">
    <div class="birthday-pop bg-[#fdfaf3] rounded-[22px] overflow-hidden shadow-2xl border border-[#efe6d0]">
      <div class="pt-7 pb-2 px-7">
        <div class="flex items-center justify-center gap-3">
          <div class="gold-line w-[36px]"></div>
          <p class="text-[10px] font-bold tracking-[0.35em] text-[#9a8a6a]">DARI ADMIN — UNTUK ANDA</p>
          <div class="gold-line w-[36px]"></div>
        </div>
        <h3 class="letter-title text-[23px] font-bold text-[#1c1c1c] mt-3 leading-[1.2]">Terdapat Sepucuk Surat<br>Untuk Anda</h3>
        <p class="text-[12px] text-zinc-500 mt-2 leading-relaxed">Surat ini ditulis khusus untuk Anda.<br>Silakan dibuka dengan tenang.</p>
      </div>
      <div class="px-8 pb-6 pt-3">
        <button type="button" onclick="bukaSurat()" class="mailbox-float w-full cursor-pointer block">
          <div id="envelopeBox" class="relative mx-auto w-full max-w-[302px] h-[198px] rounded-[12px] overflow-hidden envelope-paper" style="box-shadow: 0 22px 40px rgba(0,0,0,.22), 0 6px 12px rgba(0,0,0,.12), inset 0 1px 0 rgba(255,255,255,.7); border:1px solid #d8c493;">
            <div class="absolute left-[18px] right-[18px] top-[14px] bottom-[18px] bg-white rounded-[6px] shadow-sm border border-zinc-100 flex flex-col items-center justify-center px-4 py-3 z-[1]">
              <p class="text-[8px] font-bold tracking-[0.32em] text-zinc-400">KEPADA YTH.</p>
              <p class="letter-title text-[14px] font-bold text-[#9a0000] mt-1.5 leading-tight text-center">{{ $namaFull }}</p>
              <div class="w-[58px] h-[1px] bg-[#e8ddc5] my-2.5"></div>
              <p class="text-[8px] tracking-[0.25em] text-zinc-400 font-bold">{{ now('Asia/Jakarta')->format('d F Y') }}</p>
            </div>
            <div class="absolute left-0 right-0 bottom-0 h-[120px] envelope-pocket z-[2]" style="clip-path:polygon(0 0, 50% 64%, 100% 0, 100% 100%, 0 100%); border-top:1px solid #cdb877;"></div>
            <div class="absolute left-0 right-0 bottom-0 h-[120px] z-[2] pointer-events-none" style="background:linear-gradient(180deg, rgba(0,0,0,0.05), transparent 32%); clip-path:polygon(0 0, 50% 64%, 100% 0, 100% 100%, 0 100%);"></div>
            <div class="envelope-flap absolute left-0 right-0 top-0 h-[100px] envelope-flap-bg z-[3] border-b border-[#c4af7f]" style="clip-path:polygon(0 0, 100% 0, 50% 100%);"></div>
            <div class="seal-pop absolute left-1/2 top-[84px] -translate-x-1/2 z-[4] w-[46px] h-[46px] wax-seal wax-edge flex items-center justify-center">
              <span class="w-[30px] h-[30px] rounded-full border border-white/40 flex items-center justify-center text-white font-[800] text-[13px] tracking-widest">O</span>
            </div>
          </div>
        </button>
        <button type="button" onclick="bukaSurat()" class="w-full mt-5 bg-[#9a0000] hover:bg-[#7a0000] transition text-white font-[800] text-[13px] py-3.5 rounded-[14px] shadow-lg shadow-red-900/25">Ketuk Untuk Membuka Surat</button>
        <p class="text-center text-[10px] tracking-[0.28em] text-zinc-400 mt-3">SURAT PRIBADI • {{ now('Asia/Jakarta')->format('d F Y') }}</p>
      </div>
    </div>
  </div>

  {{-- SURAT TERBUKA --}}
  <div id="mailboxOpen" class="hidden w-full max-w-[470px] max-h-[92vh] overflow-y-auto no-scrollbar">
    <div class="letter-open relative bg-[#fffdf6] paper-texture rounded-[12px] shadow-2xl overflow-hidden" style="border:1px solid #e5d9bd;">
      <div class="absolute inset-[10px] border border-[#d9c493] rounded-[8px] pointer-events-none"></div>
      <div class="relative px-8 pt-8 pb-3">
        <div class="flex items-center justify-between">
          <p class="text-[9px] font-bold tracking-[0.32em] text-[#9a8a6a]">SURAT PRIBADI</p>
          <span class="text-[10px] font-bold tracking-widest bg-[#9a0000] text-white px-3 py-1.5 rounded-full">{{ $umur }} TAHUN HARI INI</span>
        </div>
        <div class="flex items-center gap-3 mt-5">
          <div class="gold-line flex-1"></div>
          <span class="w-[6px] h-[6px] rounded-full bg-[#c9a86a]"></span>
          <div class="gold-line flex-1"></div>
        </div>
        <p class="text-center text-[11px] tracking-[0.2em] text-zinc-400 mt-3">{{ now('Asia/Jakarta')->format('d F Y') }}</p>
        <h3 class="letter-title text-[27px] leading-[1.2] font-bold text-center text-[#1a1a1a] mt-4">Selamat Ulang Tahun,<br><span class="text-[#9a0000]">{{ $namaFull }}.</span></h3>
        <div class="flex items-center justify-center gap-3 mt-4">
          <div class="gold-line w-[40px]"></div>
          <span class="text-[11px] tracking-[0.3em] text-[#9a8a6a] font-bold">DARI ADMIN</span>
          <div class="gold-line w-[40px]"></div>
        </div>
      </div>
      <div class="relative px-8 py-2 letter-paper">
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-2">Dengan penuh hormat dan ketulusan, kami mengucapkan selamat atas bertambahnya usia Anda hari ini. Dua puluh tahun bukanlah sekadar angka. Di baliknya ada perjalanan panjang yang tidak semua orang ketahui.</p>
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-4">Ada masa-masa ketika Anda harus kuat di saat tidak ada yang menyadari bahwa Anda sedang lelah. Ada doa-doa yang Anda panjatkan dalam diam, ada kegagalan yang Anda simpan sendiri, dan ada air mata yang tidak pernah Anda ceritakan kepada siapa pun. Namun Anda tetap berdiri. Anda tetap melangkah. Dan hari ini, Anda sampai di titik ini.</p>
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-4">Kami menyadari, tidak mudah menjadi seseorang yang diharapkan untuk selalu kuat. Tidak mudah memikul tanggung jawab, menjaga nama baik keluarga, sekaligus berjuang untuk masa depan sendiri. Untuk semua itu, izinkan kami menyampaikan penghargaan yang setinggi-tingginya.</p>
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-4">Di usia yang baru ini, kami mendoakan Anda diberikan kesehatan yang terjaga, ketenangan hati, rezeki yang berkah, dan jalan yang dimudahkan untuk setiap cita-cita yang selama ini Anda perjuangkan. Semoga kedua orang tua Anda senantiasa diberikan kesehatan, agar dapat menyaksikan Anda tumbuh menjadi pribadi yang membanggakan.</p>
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-4">Jangan pernah meremehkan diri Anda sendiri. Apa yang telah Anda lalui hingga hari ini adalah bukti bahwa Anda jauh lebih kuat dari yang Anda kira. Teruslah bertumbuh dengan rendah hati, teruslah berbuat baik meski tidak selalu dilihat, dan teruslah melangkah meski perlahan.</p>
        <p class="text-[13.5px] leading-[1.95] text-[#2b2b2b] mt-4">Selamat ulang tahun. Selamat atas segala perjuangan yang tidak terlihat. Hari ini adalah milik Anda, dan Anda layak untuk merayakannya dengan penuh syukur.</p>
        <div class="mt-7 pt-5" style="border-top:1px solid #e5d9bd;">
          <p class="text-[12px] text-zinc-500 italic">Dengan hormat,</p>
          <p class="letter-title text-[18px] font-bold text-[#9a0000] mt-1">Admin</p>
          <p class="text-[11px] text-zinc-400 mt-1 leading-relaxed">Yang menaruh hormat atas perjalanan dan keteguhan Anda selama ini.</p>
        </div>
      </div>
      <div class="relative px-8 pb-7 pt-3">
        <button type="button" onclick="tutupUltah()" class="w-full bg-[#9a0000] hover:bg-[#7a0000] transition text-white font-[800] text-[13px] py-3.5 rounded-[14px] shadow-lg shadow-red-900/25">Saya Sudah Membaca Surat Ini</button>
        <p class="text-center text-[10px] tracking-[0.28em] text-zinc-400 mt-3">SURAT PRIBADI • {{ now('Asia/Jakarta')->format('d F Y') }}</p>
      </div>
    </div>
  </div>
</div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper = null;
let fotoSudahDiatur = false;
let fotoFileAsli = null;
const inputFoto = document.getElementById('inputFoto');
const imgCrop = document.getElementById('imgCrop');
const cropWrap = document.getElementById('cropWrap');
const fotoPreviewBlock = document.getElementById('fotoPreviewBlock');
const previewBulat = document.getElementById('previewBulat');
const previewPlaceholder = document.getElementById('previewPlaceholder');
const WAJIB_BIODATA = {{ $showBiodata ? 'true' : 'false' }};
function bukaEditProfil(){ document.getElementById('modalBiodata')?.classList.remove('hidden'); }
function tutupEditProfil(){ if(WAJIB_BIODATA) return; document.getElementById('modalBiodata')?.classList.add('hidden'); }
function tutupUltah(){ document.getElementById('modalUltah')?.classList.add('hidden'); sessionStorage.setItem('ultah_{{ auth()->id() }}_{{ now('Asia/Jakarta')->format('Y-m-d') }}','1'); }
function bukaSurat(){
  const colors = ['#b80000','#facc15','#ffffff','#ff6b6b','#ffd700','#ff9f43'];
  document.getElementById('envelopeBox')?.classList.add('envelope-open');
  confetti({particleCount: 90, angle: 60, spread: 75, startVelocity: 45, origin:{x:0, y:0.8}, colors, zIndex: 20000, disableForReducedMotion: true});
  setTimeout(()=>{ confetti({particleCount: 90, angle: 120, spread: 75, startVelocity: 45, origin:{x:1, y:0.8}, colors, zIndex: 20000, disableForReducedMotion: true}); }, 250);
  setTimeout(()=>{
    confetti({particleCount: 160, spread: 120, startVelocity: 50, origin:{y:0.35}, colors, zIndex: 20000, disableForReducedMotion: true});
    const end = Date.now() + 900;
    (function hujan(){
      confetti({particleCount: 3, angle: 60, spread: 60, origin:{x:0, y:0.2}, colors, zIndex: 20000, disableForReducedMotion: true});
      confetti({particleCount: 3, angle: 120, spread: 60, origin:{x:1, y:0.2}, colors, zIndex: 20000, disableForReducedMotion: true});
      if(Date.now() < end) requestAnimationFrame(hujan);
    })();
  }, 550);
  setTimeout(()=>{
    document.getElementById('mailboxClosed')?.classList.add('hidden');
    document.getElementById('mailboxOpen')?.classList.remove('hidden');
    confetti({particleCount: 80, spread: 90, startVelocity: 35, origin:{y:0.4}, colors, zIndex: 20000, disableForReducedMotion: true});
  }, 900);
}
@if($isBirthday && !$showBiodata)
window.addEventListener('load', ()=>{
  const force = new URLSearchParams(window.location.search).get('lihat_ultah');
  if(!force && sessionStorage.getItem('ultah_{{ auth()->id() }}_{{ now('Asia/Jakarta')->format('Y-m-d') }}')){ document.getElementById('modalUltah')?.classList.add('hidden'); return; }
});
@endif
function tampilkanCrop(){
  fotoPreviewBlock.classList.add('hidden');
  cropWrap.classList.remove('hidden');
}
function tampilkanPreview(){
  cropWrap.classList.add('hidden');
  fotoPreviewBlock.classList.remove('hidden');
  if(cropper){ cropper.destroy(); cropper = null; }
}
function terapkanHasilCrop(){
  if(!cropper) return;
  const canvas = cropper.getCroppedCanvas({width:600, height:600});
  const bulat = document.createElement('canvas');
  bulat.width = 600; bulat.height = 600;
  const ctx = bulat.getContext('2d');
  ctx.beginPath(); ctx.arc(300,300,300,0,Math.PI*2); ctx.closePath(); ctx.clip();
  ctx.drawImage(canvas,0,0);
  previewBulat.src = bulat.toDataURL('image/png');
  previewBulat.classList.remove('hidden');
  previewPlaceholder.classList.add('hidden');
  bulat.toBlob((blob)=>{
    const dt = new DataTransfer();
    dt.items.add(new File([blob], 'foto-formal.png', {type:'image/png'}));
    inputFoto.files = dt.files;
    fotoSudahDiatur = true;
  }, 'image/png');
}
inputFoto?.addEventListener('change', function(){
  const file = this.files[0];
  if(!file) return;
  fotoFileAsli = file;
  fotoSudahDiatur = false;
  const url = URL.createObjectURL(file);
  imgCrop.src = url;
  tampilkanCrop();
  if(cropper) cropper.destroy();
  cropper = new Cropper(imgCrop, {
    aspectRatio: 1,
    viewMode: 1,
    dragMode: 'move',
    autoCropArea: 0.85,
    responsive: true,
    background: false,
    guides: false,
    center: false,
    highlight: false,
    cropBoxMovable: false,
    cropBoxResizable: false,
    toggleDragModeOnDblclick: false
  });
});
document.getElementById('btnPutarKiri')?.addEventListener('click', ()=>{ cropper?.rotate(-90); });
document.getElementById('btnPutarKanan')?.addEventListener('click', ()=>{ cropper?.rotate(90); });
document.getElementById('btnZoomIn')?.addEventListener('click', ()=>{ cropper?.zoom(0.1); });
document.getElementById('btnZoomOut')?.addEventListener('click', ()=>{ cropper?.zoom(-0.1); });
document.getElementById('btnReset')?.addEventListener('click', ()=>{ cropper?.reset(); });
document.getElementById('btnSelesaiCrop')?.addEventListener('click', ()=>{ terapkanHasilCrop(); tampilkanPreview(); });
document.getElementById('btnBatalCrop')?.addEventListener('click', ()=>{
  if(!fotoSudahDiatur && !previewBulat.src){
    inputFoto.value = '';
    previewBulat.classList.add('hidden');
    previewPlaceholder.classList.remove('hidden');
  }
  if(fotoFileAsli && !fotoSudahDiatur && previewBulat.classList.contains('hidden')){
    inputFoto.value = '';
  }
  tampilkanPreview();
});
document.getElementById('formBiodata')?.addEventListener('submit', function(e){
  if(!cropper && !fotoSudahDiatur) return;
  if(cropper && !fotoSudahDiatur){
    e.preventDefault();
    terapkanHasilCrop();
    setTimeout(()=>{ e.target.submit(); }, 150);
  }
});
const selectJurusan = document.getElementById('selectJurusan');
const selectKelas = document.getElementById('selectKelas');
const roleId = {{ auth()->user()->role_id ?? 5 }};
const tingkat = roleId == 2 ? 'XII' : (roleId == 3 ? 'XI' : 'X');
const kelasLama = "{{ old('kelas', $anggota->kelas ?? '') }}";
function isiKelas(){
  const jurusan = selectJurusan.value;
  selectKelas.innerHTML = '';
  if(!jurusan){
    const o = document.createElement('option');
    o.value = ''; o.textContent = 'Pilih Jurusan Terlebih Dahulu';
    selectKelas.appendChild(o);
    return;
  }
  const jumlah = jurusan === 'TKL' ? 1 : 3;
  const optKosong = document.createElement('option');
  optKosong.value = ''; optKosong.textContent = 'Pilih Kelas';
  selectKelas.appendChild(optKosong);
  for(let i=1;i<=jumlah;i++){
    const val = tingkat + ' ' + jurusan + ' ' + i;
    const o = document.createElement('option');
    o.value = val; o.textContent = val;
    if(kelasLama === val) o.selected = true;
    selectKelas.appendChild(o);
  }
  if(kelasLama && !selectKelas.value){
    const o = document.createElement('option');
    o.value = kelasLama; o.textContent = kelasLama + ' (Sebelumnya)';
    o.selected = true;
    selectKelas.appendChild(o);
  }
}
selectJurusan?.addEventListener('change', isiKelas);
isiKelas();
</script>
</body>
</html>