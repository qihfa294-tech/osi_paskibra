<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>E-KAS - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
<style>
html,body{max-width:100%;overflow-x:hidden}
body{font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased}
.bg-paskibra{background:#7a0000;min-height:100vh}
.glass-card{background:#fff;border:1px solid #e7e5e4}
.gshock{font-family:'JetBrains Mono',monospace;font-weight:800;letter-spacing:.02em;font-variant-numeric:tabular-nums}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
.anim-fade{animation:fadeUp .35s ease both}
@keyframes fadeUp{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
@keyframes pop{0%{transform:scale(.97);opacity:0}100%{transform:scale(1);opacity:1}}
.anim-pop{animation:pop .25s ease both}
.btn-setor{background:#B91C1C;color:#fff;font-weight:800;box-shadow:0 6px 14px rgba(185,28,28,.3)}
.btn-setor:active{transform:scale(.96)}
.btn-filter{background:#111827;color:#fff}
.btn-nominal:active{transform:scale(.94)}
.btn-nominal.is-selected{background:#9A0000!important;color:#fff!important}
.list-angkatan{background:#fff;border:1px solid #e5e5e5;overflow:hidden}
.row-angkatan{border-bottom:1px solid #f0f0f0}
.row-angkatan:last-child{border-bottom:0}
.row-angkatan.active{background:#FFF1F2;border-left:4px solid #9A0000}
.box-riwayat{background:#F8FAFC;border:1px solid #E2E8F0;border-radius:14px;padding:14px}
.box-penjelasan{background:#fff;border:1px solid #e7e5e4;border-radius:14px;overflow:hidden}
.tier-card{position:relative;overflow:hidden;isolation:isolate;transition:background .3s ease, color .3s ease}
.tier-card::before{content:'';position:absolute;inset:0;background:var(--tier-shine);transform:translateX(-120%);z-index:0;pointer-events:none}
.tier-card > *{position:relative;z-index:1}
.tier-bronze::before{display:none}
.tier-silver::before{animation:tierShine 4.5s ease-in-out infinite}
.tier-gold::before{animation:tierShine 3.2s ease-in-out infinite}
.tier-black::before{animation:tierShine 2.6s ease-in-out infinite}
@keyframes tierShine{0%{transform:translateX(-120%)}55%{transform:translateX(120%)}100%{transform:translateX(120%)}}
.tier-wrap{position:relative;overflow:hidden;border-radius:18px}
.tier-card.is-tearing{animation:tearShake .45s ease .1s 2}
@keyframes tearShake{0%,100%{transform:translateX(0) rotate(0)}25%{transform:translateX(-3px) rotate(-.6deg)}75%{transform:translateX(3px) rotate(.6deg)}}
.tear-piece{position:absolute;top:0;bottom:0;width:51%;z-index:20;pointer-events:none;display:none;border-radius:18px;overflow:hidden}
.tear-piece.show{display:block}
.tear-left{left:0;clip-path:polygon(0 0,100% 0,88% 100%,0 100%);animation:tearFallLeft 1s cubic-bezier(.3,.7,.5,1) .55s both}
.tear-right{right:0;clip-path:polygon(12% 0,100% 0,100% 100%,0 100%);animation:tearFallRight 1s cubic-bezier(.3,.7,.5,1) .55s both}
@keyframes tearFallLeft{0%{transform:translate(0,0) rotate(0);opacity:1}30%{transform:translate(-6px,-4px) rotate(-2deg)}100%{transform:translate(-14px,120%) rotate(-14deg);opacity:0}}
@keyframes tearFallRight{0%{transform:translate(0,0) rotate(0);opacity:1}30%{transform:translate(6px,-4px) rotate(2deg)}100%{transform:translate(14px,120%) rotate(14deg);opacity:0}}
.tear-line{position:absolute;top:0;bottom:0;left:50%;width:2px;background:rgba(255,255,255,.9);z-index:21;display:none;transform:translateX(-50%)}
.tear-line.show{display:block;animation:tearLine .4s ease .5s both}
@keyframes tearLine{from{opacity:0;transform:translateX(-50%) scaleY(0)}to{opacity:1;transform:translateX(-50%) scaleY(1)}}
.tier-card.is-dropping{animation:dropCard .85s cubic-bezier(.2,1.2,.3,1) both}
@keyframes dropCard{0%{transform:translateY(-110vh) rotate(-6deg) scale(.9);opacity:0}55%{transform:translateY(8px) rotate(1deg) scale(1.02);opacity:1}70%{transform:translateY(-8px) rotate(0)}85%{transform:translateY(3px)}100%{transform:translateY(0) rotate(0) scale(1);opacity:1}}
.tier-card.drop-black{animation:dropCardBlack 1s cubic-bezier(.2,1.1,.3,1) both, blackGlow 2s ease 1s 2}
@keyframes dropCardBlack{0%{transform:translateY(-130vh) rotate(-10deg) scale(.85);opacity:0}50%{transform:translateY(14px) rotate(2deg) scale(1.04);opacity:1}65%{transform:translateY(-12px)}80%{transform:translateY(6px)}100%{transform:translateY(0) scale(1);opacity:1}}
@keyframes blackGlow{0%,100%{box-shadow:0 12px 30px rgba(0,0,0,.35)}50%{box-shadow:0 0 0 4px rgba(212,175,55,.8),0 0 90px rgba(212,175,55,1)}}
.tier-card.drop-gold{animation:dropCard .8s cubic-bezier(.2,1.2,.3,1) both, goldPulse 1.6s ease .8s 2}
@keyframes goldPulse{0%,100%{box-shadow:0 12px 30px rgba(201,168,106,.35)}50%{box-shadow:0 0 0 4px rgba(246,215,139,.6),0 0 60px rgba(246,215,139,.9)}}
.tier-particles{position:absolute;inset:0;pointer-events:none;overflow:hidden;z-index:30;display:none}
.tier-card.is-dropping .tier-particles, .tier-card.drop-black .tier-particles, .tier-card.drop-gold .tier-particles{display:block}
.tier-particles i{position:absolute;bottom:20%;width:8px;height:8px;border-radius:9999px;animation:burst 1s ease-out both}
@keyframes burst{0%{transform:translate(0,0) scale(1);opacity:1}100%{transform:translate(var(--tx),var(--ty)) scale(0);opacity:0}}
.upgrade-banner{animation:fadeUp .5s .2s both}
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? auth()->user()->name ?? auth()->user()->username ?? 'CAPAS';
$angkatan = $angkatanFix ?? '2023';
$masukVal = $totalMasuk ?? 0; $keluarVal = $totalKeluar ?? 0;
$myTotalVal = $myTotal ?? 0; $myPendingVal = $myPending ?? 0;
$filterAngkatan = $filterAngkatan ?? request('angkatan','semua'); $search = $search ?? '';
$filterTipe = $filterTipe ?? request('tipe','semua');
$perAngkatan = $perAngkatan ?? collect(); $trackRecord = $trackRecord ?? collect(); $myKas = $myKas ?? collect();
$isModeKeuangan = in_array($filterTipe, ['masuk','keluar']);
$listKeuangan = $isModeKeuangan ? $trackRecord->filter(function($k) use ($filterTipe){ return ($k->tipe ?? 'masuk') == $filterTipe; })->values() : collect();
$listRiwayat = $myKas;
$targetKas = $target ?? 100000;
$tierPersen = $targetKas > 0 ? min(100, round($myTotalVal / $targetKas * 100)) : 0;
$totalKePaskibraAngkatan = $masukVal * 0.2;
$saldoVal = $masukVal - $keluarVal - $totalKePaskibraAngkatan;
if($tierPersen >= 100){
  $tier='BLACK'; $tierNext=null; $tierSisa=0; $tierPersen=100;
  $tierBg='linear-gradient(135deg,#0a0a0a 0%,#1a1a1a 60%,#2a220a 100%)'; $tierText='#F5E6C4'; $tierDesc='Lunas.';
  $tierGlow='0 12px 30px rgba(0,0,0,.35)'; $tierShine='linear-gradient(105deg, transparent 40%, rgba(212,175,55,.55) 50%, transparent 60%)'; $tierClass='tier-black';
} elseif($tierPersen >= 50){
  $tier='GOLD'; $tierNext='BLACK'; $tierSisa=$targetKas-$myTotalVal;
  $tierBg='linear-gradient(135deg,#F6D78B 0%,#C9A86A 45%,#8A6D2B 100%)'; $tierText='#2e2410'; $tierDesc='Sisa Rp'.number_format($tierSisa,0,',','.').' menuju BLACK.';
  $tierGlow='0 12px 30px rgba(201,168,106,.35)'; $tierShine='linear-gradient(105deg, transparent 40%, rgba(255,255,255,.6) 50%, transparent 60%)'; $tierClass='tier-gold';
} elseif($tierPersen >= 25){
  $tier='SILVER'; $tierNext='GOLD'; $tierSisa=ceil($targetKas*0.5)-$myTotalVal;
  $tierBg='linear-gradient(135deg,#FFFFFF 0%,#C9CDD1 45%,#8A9299 100%)'; $tierText='#23272c'; $tierDesc='Sisa Rp'.number_format($tierSisa,0,',','.').' menuju GOLD.';
  $tierGlow='0 12px 30px rgba(201,205,209,.35)'; $tierShine='linear-gradient(105deg, transparent 40%, rgba(255,255,255,.8) 50%, transparent 60%)'; $tierClass='tier-silver';
} else {
  $tier='BRONZE'; $tierNext='SILVER'; $tierSisa=ceil($targetKas*0.25)-$myTotalVal;
  $tierBg='linear-gradient(135deg,#C08A5A 0%,#8F6B4A 45%,#5C3A1E 100%)'; $tierText='#fff'; $tierDesc='Sisa Rp'.number_format($tierSisa,0,',','.').' menuju SILVER.';
  $tierGlow='0 12px 30px rgba(143,107,74,.3)'; $tierShine='none'; $tierClass='tier-bronze';
}
$keAngkatan = $myTotalVal * 0.8;
$kePaskibra = $myTotalVal * 0.2;
$naikTier = session('naik_tier') ?? null;
$tierLama = session('tier_lama') ?? null;
$isUpgrade = $naikTier && $tierLama && $naikTier !== $tierLama;
$oldBgMap = ['BRONZE'=>'linear-gradient(135deg,#C08A5A 0%,#8F6B4A 45%,#5C3A1E 100%)','SILVER'=>'linear-gradient(135deg,#FFFFFF 0%,#C9CDD1 45%,#8A9299 100%)','GOLD'=>'linear-gradient(135deg,#F6D78B 0%,#C9A86A 45%,#8A6D2B 100%)'];
$oldTextMap = ['BRONZE'=>'#fff','SILVER'=>'#23272c','GOLD'=>'#2e2410'];
$startBg = $isUpgrade ? ($oldBgMap[$tierLama] ?? $tierBg) : $tierBg;
$startText = $isUpgrade ? ($oldTextMap[$tierLama] ?? $tierText) : $tierText;
@endphp

<div class="lg:hidden flex justify-between items-center p-4 sticky top-0 z-20 bg-[#7a0000] border-b border-white/10">
<div class="flex items-center gap-3 min-w-0"><img src="{{ asset('logo_osi.png') }}" class="w-8 h-8 object-contain shrink-0"><span class="font-extrabold italic text-white text-[14px]">OSI</span></div>
<button id="btnOpen" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center shrink-0">☰</button>
</div>
<div id="overlay" class="fixed inset-0 bg-black/60 z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen relative">
<div id="sidebar" class="w-[85%] max-w-[280px] lg:w-[280px] bg-[#4a0000] lg:bg-black/30 border-r border-white/10 fixed inset-y-0 left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col h-[100dvh] lg:h-screen shrink-0">
    <div class="p-7 flex-1 overflow-y-auto no-scrollbar">
        <div class="flex items-center justify-between"><div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-10 h-10 object-contain"><div><h1 class="font-extrabold text-white text-[16px] italic leading-none">OSI</h1><p class="text-[10px] font-bold tracking-[0.2em] text-white/60 mt-1">BY PASKIBRA NESTA</p></div></div><button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center">✕</button></div>
        <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">MENU CAPAS</p>
            <div class="space-y-1.5">
                <a href="{{ route('capas.dashboard') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Dashboard</a>
                @if(isFeatureEnabled('idc'))<a href="{{ route('capas.idc') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">IDC Saya</a>@endif
                @if(isFeatureEnabled('kelola_anggota'))<a href="{{ route('capas.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Anggota</a>@endif
                @if(isFeatureEnabled('e_kas'))<a href="{{ route('capas.kas') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px]">E-KAS</a>@endif
                @if(isFeatureEnabled('absensi'))<a href="{{ route('capas.absensi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Absensi</a>@endif
                @if(isFeatureEnabled('materi'))<a href="{{ route('capas.materi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Materi</a>@endif
            </div>
        </div>
        <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4"><p class="text-[10px] font-bold tracking-widest text-amber-300">CAPAS - {{ $angkatan }}</p><p class="text-[11px] text-white/60 mt-2">Tingkatan: <span class="font-bold text-white">{{ $tier }}</span></p><p class="text-[11px] text-white/60 mt-1">Target: Rp{{ number_format($targetKas,0,',','.') }}</p></div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0"><div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10"><div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold truncate">{{ Str::limit($namaFull, 18) }}</p><p class="text-white/40 text-[9px] mt-1">CAPAS - {{ $angkatan }}</p></div></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white shrink-0">X</button></form></div></div>
</div>

<div class="flex-1 lg:ml-[280px] w-full flex flex-col min-h-screen relative px-4 lg:px-8 pt-6 pb-8 min-w-0">
    <div class="anim-fade">
        <p class="text-[12px] text-white/60">CAPAS - ANGKATAN {{ $angkatan }}</p>
        <h1 class="text-white font-extrabold text-[26px] leading-tight mt-1">E-KAS</h1>
        <p class="text-[12px] text-white/60 mt-1">Target: Rp{{ number_format($targetKas,0,',','.') }} | Sudah dibayar: Rp{{ number_format($myTotalVal,0,',','.') }} | Sisa: Rp{{ number_format(max(0,$targetKas-$myTotalVal),0,',','.') }}</p>
        @if(session('success'))<div class="mt-3 inline-flex bg-emerald-50 border border-emerald-200 text-emerald-700 text-[12px] font-semibold px-3 py-2 rounded-[10px] anim-pop">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="mt-3 inline-flex bg-red-50 border border-red-200 text-[#9a0000] text-[12px] font-semibold px-3 py-2 rounded-[10px]">{{ $errors->first() }}</div>@endif
        @if($isUpgrade)<div class="upgrade-banner mt-3 inline-flex bg-amber-50 border border-amber-200 text-amber-800 text-[12px] font-bold px-3 py-2 rounded-[10px]">🎉 Tingkatan naik: {{ $tierLama }} → {{ $naikTier }}</div>@endif
    </div>

    <div class="tier-wrap mt-5" id="tierWrap">
      <div id="mainTierCard" class="tier-card {{ $tierClass }} rounded-[18px] p-5 relative overflow-hidden anim-fade" data-new-tier="{{ $tier }}" data-old-tier="{{ $tierLama ?? $tier }}" data-is-upgrade="{{ $isUpgrade ? '1' : '0' }}" style="background:{{ $startBg }}; color:{{ $startText }}; box-shadow:{{ $tierGlow }}; --tier-shine:{{ $tierShine }};">
        <div class="tier-particles" id="tierParticles"></div>
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-2"><img src="{{ asset('logo_osi.png') }}" class="w-6 h-6 object-contain"><span class="text-[11px] font-bold opacity-90">Paskibra Nesta</span></div>
            <span id="tierBadge" class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-black/15 shrink-0">{{ $isUpgrade ? $tierLama : $tier }}</span>
        </div>
        <div class="flex items-start justify-between gap-3 mt-5">
            <div class="min-w-0 flex-1">
                <p class="text-[12px] opacity-70">Total yang sudah lunas</p>
                <p id="saldoText" data-target="{{ $myTotalVal }}" class="gshock text-[30px] lg:text-[34px] leading-none mt-1 break-words">Rp0</p>
                <p class="text-[12px] opacity-70 mt-2"><span id="tierLabelInline">{{ $isUpgrade ? $tierLama : $tier }}</span> - {{ $tierPersen }}% dari target</p>
            </div>
            @if($tier != 'BLACK')
            <button onclick="openSetor()" class="btn-setor shrink-0 text-white font-bold text-[12px] px-5 py-3 rounded-full">Setor Kas</button>
            @else
            <span class="shrink-0 inline-flex text-[11px] font-bold px-3.5 py-2.5 rounded-full border bg-black/10">Lunas</span>
            @endif
        </div>
        <div class="flex items-end justify-between gap-4 mt-5 pt-4 border-t border-white/15">
            <div class="min-w-0 flex-1"><p class="text-[11px] opacity-60">Nama Anggota</p><p class="text-[15px] font-bold leading-snug break-words">{{ $namaFull }}</p><p class="text-[11px] opacity-60 mt-1">CAPAS Angkatan {{ $angkatan }}</p></div>
            <div class="text-right shrink-0"><p class="text-[11px] opacity-60">Tingkatan</p><p id="tierBigLabel" class="text-[16px] font-extrabold leading-none mt-1">{{ $isUpgrade ? $tierLama : $tier }}</p><p class="text-[11px] opacity-60 mt-1">{{ $tierPersen }}%</p></div>
        </div>
        <div class="mt-4">
            <div class="flex items-center justify-between text-[11px] font-semibold"><span class="opacity-80">{{ $tier }}{{ $tierNext ? ' menuju '.$tierNext : '' }}</span><span class="opacity-80">{{ $tierPersen }}%</span></div>
            <div class="mt-1.5 h-[8px] rounded-full bg-black/15 overflow-hidden"><div class="h-full rounded-full bg-white/80" style="width:{{ $tierPersen }}%"></div></div>
            <p class="text-[12px] opacity-70 mt-2">{{ $tierDesc }}</p>
        </div>
        @if($myPendingVal > 0)
        <div class="mt-4 rounded-[10px] px-3 py-2.5 bg-black/10 text-[12px]">Setoran Rp{{ number_format($myPendingVal,0,',','.') }} masih menunggu validasi.</div>
        @endif
      </div>
      <div class="tear-piece tear-left" id="tearLeft"></div>
      <div class="tear-piece tear-right" id="tearRight"></div>
      <div class="tear-line" id="tearLine"></div>
    </div>

    <div class="glass-card rounded-[20px] mt-4 p-5 anim-fade">
      <div class="flex justify-between items-center"><h3 class="font-bold text-[14px]">Pembagian Dana</h3><span class="text-[10px] font-bold bg-zinc-100 px-2.5 py-1 rounded-full">80% / 20%</span></div>
      <div class="grid grid-cols-2 gap-3 mt-3">
        <div class="bg-zinc-50 border rounded-[12px] p-3 min-w-0"><p class="text-[11px] text-zinc-500">80% - Kas Angkatan</p><p class="font-bold text-[15px] break-words">Rp{{ number_format($keAngkatan,0,',','.') }}</p></div>
        <div class="bg-zinc-50 border rounded-[12px] p-3 min-w-0"><p class="text-[11px] text-zinc-500">20% - Kas Paskibra</p><p class="font-bold text-[15px] break-words">Rp{{ number_format($kePaskibra,0,',','.') }}</p></div>
      </div>
      <p class="text-[11px] text-zinc-500 mt-3">Rincian pembagian terdapat pada bagian Penjelasan di bawah.</p>
    </div>

    <div class="glass-card rounded-[20px] mt-4 px-5 pt-5 pb-5 lg:px-6 flex-1 anim-fade">
        <div>
            <div class="flex items-center justify-between"><h3 class="font-bold text-[14px] text-zinc-900">Kas Angkatan</h3><span class="text-[11px] font-semibold text-zinc-900 bg-zinc-100 px-2.5 py-1 rounded-full">{{ $perAngkatan->count() }} Angkatan</span></div>
            <div class="list-angkatan mt-3 rounded-[12px]">
                @forelse($perAngkatan as $a)
                <a href="{{ route('capas.kas') }}?angkatan={{ $a->angkatan }}&tipe=semua" class="row-angkatan {{ $filterAngkatan == $a->angkatan ? 'active' : '' }} flex items-center justify-between px-4 py-3.5 gap-3">
                    <span class="flex items-center gap-3 min-w-0"><span class="w-2.5 h-2.5 rounded-full bg-[#9A0000] inline-block shrink-0"></span><span class="text-[13px] font-bold text-zinc-900 truncate">Angkatan {{ $a->angkatan }}</span></span>
                    <span class="text-[13px] font-bold text-zinc-900 shrink-0">Rp{{ number_format($a->total ?? 0,0,',','.') }}</span>
                </a>
                @empty
                <p class="px-4 py-4 text-[12px] text-zinc-500 text-center font-medium">Belum tersedia data angkatan.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-[14px] mt-6 p-4 lg:p-5 bg-[#FAFAF9] border border-[#e7e5e4]">
            <p class="text-[11px] text-zinc-500">Kas Angkatan {{ $filterAngkatan == 'semua' ? 'Semua' : $filterAngkatan }}</p>
            <p class="gshock text-[28px] leading-none mt-2 break-words">Rp{{ number_format($saldoVal,0,',','.') }}</p>
            <p class="text-[11px] text-zinc-500 mt-1.5">Saldo kas bersama angkatan. Bukan saldo pribadi. Saldo pribadi terdapat pada kartu di atas.</p>
            <div class="space-y-2.5 text-[13px] mt-4">
                <div class="flex justify-between gap-3 bg-white border border-zinc-100 rounded-[10px] px-3 py-2"><span class="text-zinc-600">Total Pemasukan</span><span class="font-bold text-emerald-600 shrink-0">+ Rp{{ number_format($masukVal,0,',','.') }}</span></div>
                <div class="flex justify-between gap-3 bg-white border border-zinc-100 rounded-[10px] px-3 py-2"><span class="text-zinc-600">Dialokasikan ke Kas Paskibra (20%)</span><span class="font-bold text-amber-600 shrink-0">- Rp{{ number_format($totalKePaskibraAngkatan,0,',','.') }}</span></div>
                <div class="flex justify-between gap-3 bg-white border border-zinc-100 rounded-[10px] px-3 py-2"><span class="text-zinc-600">Total Pengeluaran</span><span class="font-bold text-red-600 shrink-0">- Rp{{ number_format($keluarVal,0,',','.') }}</span></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-4">
                <a href="{{ route('capas.kas') }}?angkatan={{ $filterAngkatan }}&tipe=masuk#keuangan" class="rounded-[14px] p-4 flex flex-col gap-1 border-2 {{ $filterTipe=='masuk' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white border-emerald-200' }}"><span class="text-[11px] font-bold tracking-widest">PEMASUKAN</span><span class="text-[16px] font-bold leading-none mt-1 break-words">Rp{{ number_format($masukVal,0,',','.') }}</span></a>
                <a href="{{ route('capas.kas') }}?angkatan={{ $filterAngkatan }}&tipe=keluar#keuangan" class="rounded-[14px] p-4 flex flex-col gap-1 border-2 {{ $filterTipe=='keluar' ? 'bg-red-600 text-white border-red-600' : 'bg-white border-red-200' }}"><span class="text-[11px] font-bold tracking-widest">PENGELUARAN</span><span class="text-[16px] font-bold leading-none mt-1 break-words">Rp{{ number_format($keluarVal,0,',','.') }}</span></a>
            </div>
            <p class="text-[11px] text-zinc-500 mt-3">Perhitungan hanya mencakup transaksi berstatus Lunas. Transaksi Pending belum diperhitungkan.</p>
            @if($isModeKeuangan)
            <div id="keuangan" class="mt-5 pt-5 border-t-2 {{ $filterTipe=='masuk' ? 'border-emerald-500' : 'border-red-500' }} anim-pop">
                <div class="flex items-start justify-between gap-3"><div><h3 class="font-bold text-[15px]">{{ $filterTipe=='masuk' ? 'Riwayat Pemasukan' : 'Riwayat Pengeluaran' }}</h3><p class="text-[11px] text-zinc-500 mt-1">{{ $filterAngkatan != 'semua' ? 'Angkatan '.$filterAngkatan : 'Semua Angkatan' }}</p></div><a href="{{ route('capas.kas') }}?angkatan={{ $filterAngkatan }}&tipe=semua" class="shrink-0 text-[11px] font-bold bg-zinc-900 text-white px-3 py-2 rounded-full">Tutup</a></div>
                <div class="mt-4 space-y-2.5">
                    @forelse($listKeuangan as $k)
                    <div class="bg-white border border-zinc-200 rounded-[12px] px-4 py-3 flex items-center justify-between gap-3"><div class="min-w-0"><p class="text-[13px] font-bold truncate">{{ $k->nama_pembayar ?? 'Tidak Diketahui' }}</p><p class="text-[11px] text-zinc-400 mt-0.5 truncate">{{ $k->keterangan ?? 'Iuran Kas' }} - {{ $k->created_at ? $k->created_at->format('d M Y H:i') : '-' }}</p></div><p class="font-bold text-[13px] {{ ($k->tipe ?? 'masuk') == 'keluar' ? 'text-red-600' : 'text-emerald-600' }} shrink-0">{{ ($k->tipe ?? 'masuk') == 'keluar' ? '-' : '+' }}Rp{{ number_format($k->nominal ?? 0,0,',','.') }}</p></div>
                    @empty
                    <div class="rounded-[12px] border border-dashed border-zinc-200 bg-white p-5 text-center"><p class="text-[12px] text-zinc-500 font-semibold">Belum terdapat {{ $filterTipe=='masuk' ? 'pemasukan' : 'pengeluaran' }} untuk angkatan ini.</p></div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        <div id="riwayat" class="mt-6 pt-6 border-t border-zinc-100">
            <div class="flex items-center justify-between gap-3"><h3 class="font-bold text-[14px]">Riwayat Setoran Anda</h3><span class="text-[10px] font-bold bg-zinc-900 text-white px-3 py-1.5 rounded-full shrink-0">{{ $listRiwayat->count() }} Transaksi</span></div>
            <div class="box-riwayat mt-4">
                <form method="GET" action="{{ route('capas.kas') }}" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan keterangan..." class="w-full bg-white border border-zinc-200 rounded-[10px] px-3 py-2.5 text-[12px] outline-none focus:border-[#9a0000] min-w-0">
                    <input type="hidden" name="angkatan" value="{{ $filterAngkatan }}"><input type="hidden" name="tipe" value="{{ $filterTipe }}">
                    <button class="btn-filter px-5 py-2.5 rounded-[10px] text-[12px] font-bold shrink-0">Filter</button>
                </form>
                <div class="bg-white border border-slate-200 rounded-[10px] overflow-hidden mt-4">
                    @forelse($listRiwayat as $k)
                    @php $st = strtolower($k->status ?? ''); $badge = $st=='lunas' ? 'bg-emerald-100 text-emerald-700' : (in_array($st,['ditolak','rejected','tolak']) ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'); $label = $st=='lunas' ? 'LUNAS' : (in_array($st,['ditolak','rejected','tolak']) ? 'DITOLAK' : 'PENDING'); @endphp
                    <div class="px-4 py-4 border-b border-slate-100 last:border-b-0 flex justify-between gap-3"><div class="min-w-0"><p class="text-[12px] font-bold">{{ $k->created_at ? $k->created_at->translatedFormat('l, d F Y') : '-' }}</p><p class="text-[11px] text-zinc-500 mt-1 break-words">{{ $k->keterangan ?? 'Iuran Kas' }} - ID #{{ $k->id }}</p>@if(!empty($k->bukti))<a href="{{ asset('storage/'.$k->bukti) }}" target="_blank" class="inline-flex mt-2 text-[11px] font-bold text-blue-600 border border-blue-200 bg-blue-50 px-3 py-1.5 rounded-full">Lihat Bukti</a>@endif</div><div class="text-right shrink-0"><p class="font-bold text-[13px]">Rp{{ number_format($k->nominal ?? 0,0,',','.') }}</p><span class="inline-block text-[10px] font-bold px-3 py-1.5 rounded-full {{ $badge }} mt-1">{{ $label }}</span></div></div>
                    @empty
                    <div class="p-6 text-center"><p class="text-[12px] text-zinc-500 font-semibold">Belum terdapat riwayat transaksi.</p><p class="text-[11px] text-zinc-400 mt-1">Silakan melakukan setoran pertama melalui tombol Setor Kas di atas.</p></div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-zinc-100">
          <h3 class="font-bold text-[14px]">Riwayat Kas Saya</h3>
          <p class="text-[11px] text-zinc-500 mt-1">Naik jabatan dimulai dari awal. Bukan lanjutan.</p>
          <div class="mt-3 space-y-2">
            @forelse($riwayatJabatan ?? [] as $rj)
            <div class="flex justify-between items-center bg-white border rounded-[12px] px-4 py-3 gap-3"><div class="min-w-0"><p class="font-bold text-[13px] truncate">{{ $rj->jabatan }} {{ $rj->periode }}</p><p class="text-[11px] text-zinc-500">{{ $rj->total }} / {{ $rj->target }}</p></div><span class="text-[10px] font-bold px-3 py-1.5 rounded-full shrink-0 {{ $rj->tier=='BLACK' ? 'bg-black text-white' : 'bg-zinc-100' }}">{{ $rj->tier }}</span></div>
            @empty
            <div class="bg-zinc-50 border rounded-[12px] px-4 py-3 flex justify-between items-center gap-3"><div class="min-w-0"><p class="font-bold text-[13px]">CAPAS {{ $angkatan }} - Aktif</p><p class="text-[11px] text-zinc-500">Periode pertama. Target Rp{{ number_format($targetKas,0,',','.') }}</p></div><span class="text-[10px] font-bold bg-zinc-900 text-white px-3 py-1.5 rounded-full shrink-0">{{ $tier }}</span></div>
            @endforelse
          </div>
        </div>

        <div class="mt-6 pt-6 border-t border-zinc-100">
            <div class="box-penjelasan">
                <div class="bg-zinc-900 text-white px-4 py-3 flex items-center justify-between gap-3"><h3 class="font-bold text-[14px]">Penjelasan dan Ketentuan E-KAS</h3><span class="bg-white text-zinc-900 text-[10px] font-bold px-2 py-1 rounded-full shrink-0">WAJIB DIBACA</span></div>
                <div class="p-4 space-y-4 text-[12px] leading-relaxed text-zinc-700">
                    <p>Mohon membaca seluruh ketentuan berikut dengan saksama. Ketentuan ini disusun untuk menjaga transparansi, ketertiban pencatatan, dan keadilan bagi seluruh anggota.</p>
                    <div><p class="font-bold text-zinc-900">1. Setoran atas nama sendiri</p><p class="mt-1">Setoran yang dikirimkan melalui akun Anda dicatat atas nama Anda. Setoran tidak dapat dialihkan atau dicatatkan atas nama anggota lain. Anggota lain wajib melakukan setoran melalui akun masing-masing.</p></div>
                    <div><p class="font-bold text-zinc-900">2. Batas nominal setoran</p><p class="mt-1">Nominal dalam satu transaksi minimal Rp1.000 dan maksimal Rp10.000.000. Apabila melebihi batas tersebut, silakan membagi setoran ke dalam beberapa transaksi.</p></div>
                    <div><p class="font-bold text-zinc-900">3. Metode pembayaran</p><p class="mt-1">Tersedia tiga metode: Tunai, QRIS, atau Transfer. Seluruh metode memiliki keabsahan yang sama, sepanjang disertai bukti pembayaran yang jelas.</p></div>
                    <div><p class="font-bold text-zinc-900">4. Bukti pembayaran</p><p class="mt-1">Setiap setoran wajib disertai bukti pembayaran berupa foto struk, bukti transfer, atau tangkapan layar dari aplikasi perbankan. Bukti wajib memuat nominal dan tanggal transaksi secara jelas. Apabila bukti tidak jelas atau terpotong, proses validasi dapat tertunda.</p></div>
                    <div><p class="font-bold text-zinc-900">5. Penetapan bulan setoran</p><p class="mt-1">Bulan setoran ditetapkan secara otomatis oleh sistem berdasarkan tanggal pengiriman setoran. Contoh: setoran pada tanggal 5 November dicatat sebagai setoran bulan November.</p></div>
                    <div><p class="font-bold text-zinc-900">6. Status Pending</p><p class="mt-1">Setiap setoran yang baru dikirimkan berstatus Pending. Status tersebut berarti setoran telah diterima sistem, tetapi belum ditambahkan ke total dan tingkatan karena masih menunggu pemeriksaan oleh PPS atau PPL. Apabila setoran masih Pending setelah 2 x 24 jam, mohon menghubungi bendahara dengan menyertakan bukti setoran.</p></div>
                    <div><p class="font-bold text-zinc-900">7. Validasi</p><p class="mt-1">Setelah bendahara memeriksa bukti dan memastikan dana diterima, status setoran diubah menjadi Lunas. Hanya setoran berstatus Lunas yang diperhitungkan ke dalam total dan tingkatan.</p></div>
                    <div><p class="font-bold text-zinc-900">8. Setoran tidak dapat diubah atau dihapus</p><p class="mt-1">Mohon memeriksa kembali nominal, metode pembayaran, dan bukti sebelum menekan tombol Kirim Setoran. Setelah terkirim, setoran tidak dapat diubah maupun dihapus. Apabila terjadi kesalahan nominal, mohon tidak melakukan setoran tambahan sebagai koreksi, melainkan menghubungi bendahara.</p></div>
                    <div><p class="font-bold text-zinc-900">9. Tingkatan</p><p class="mt-1">Tingkatan merupakan penanda jumlah total setoran berstatus Lunas, bukan penanda perbedaan kedudukan antar anggota. Ketentuan tingkatan dihitung dari persentase terhadap target periode: BRONZE 0% - 24%, SILVER 25% - 49%, GOLD 50% - 99%, BLACK 100% (Lunas). Contoh target Rp100.000: BRONZE Rp0 - Rp24.999, SILVER Rp25.000 - Rp49.999, GOLD Rp50.000 - Rp99.999, BLACK Rp100.000. Tingkatan meningkat secara otomatis apabila total setoran berstatus Lunas bertambah.</p></div>
                    <div><p class="font-bold text-zinc-900">10. Pembagian 80% / 20%</p><p class="mt-1">Setiap setoran yang lunas dibagi menjadi 80% untuk Kas Angkatan dan 20% untuk Kas Paskibra. Pembagian dihitung dari jumlah yang sudah terkumpul, bukan dari target, sehingga tidak menimbulkan hutang. Persentase tersebut dapat berubah untuk periode berikutnya melalui musyawarah bersama CAPAS, PPL, dan PPS. Perubahan tidak dapat dilakukan di tengah periode yang sedang berjalan.</p></div>
                    <div><p class="font-bold text-zinc-900">11. Pengeluaran dan Alokasi</p><p class="mt-1">Pengeluaran adalah dana yang digunakan untuk kebutuhan kegiatan. Alokasi 20% ke Kas Paskibra bukan merupakan pengeluaran, melainkan perpindahan dana antar dompet. Dana tersebut tetap tercatat dan dapat digunakan untuk kebutuhan bersama. Oleh karena itu, pengeluaran dapat bernilai Rp0 apabila belum terdapat penggunaan dana, meskipun alokasi 20% telah dilakukan.</p></div>
                    <div><p class="font-bold text-zinc-900">12. Kenaikan jabatan</p><p class="mt-1">Kenaikan jabatan dari CAPAS ke PPL, atau dari PPL ke PPS, dicatat sebagai periode baru. Tingkatan dimulai kembali dari awal. Hal tersebut berlaku karena dompet, kebutuhan, dan periode yang digunakan berbeda.</p></div>
                    <p class="text-[11px] text-zinc-500">Dengan menekan tombol Kirim Setoran, Anda menyatakan telah membaca dan menyetujui seluruh ketentuan di atas. Apabila terdapat hal yang belum jelas, silakan menghubungi bendahara untuk memperoleh penjelasan lebih lanjut.</p>
                </div>
            </div>
        </div>
        <p class="text-center text-[10px] tracking-[0.2em] text-zinc-300 mt-8">PASKIBRA NESTA - 2026</p>
    </div>
</div>
</div>

<div id="modalSetor" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60">
    <div class="bg-white rounded-[20px] w-full max-w-[400px] max-h-[90vh] overflow-y-auto no-scrollbar p-5 anim-pop">
        <div class="flex items-start justify-between gap-3"><div class="min-w-0"><h3 class="font-bold text-[18px]">Setor Kas</h3><p class="text-[11px] text-zinc-500 mt-0.5 truncate">{{ $namaFull }} - Angkatan {{ $angkatan }}</p></div><button onclick="closeSetor()" class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center shrink-0">✕</button></div>
        <form method="POST" action="{{ route('capas.kas.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="bulan" value="{{ now()->format('Y-m') }}">
            <div class="bg-zinc-50 border border-zinc-200 rounded-[10px] px-3 py-2.5 flex justify-between text-[12px]"><span class="text-zinc-500">Bulan Setoran</span><span class="font-bold">{{ now()->format('F Y') }} - Otomatis</span></div>
            <div><p class="text-[11px] font-bold tracking-widest text-zinc-500">1. NOMINAL SETORAN</p><div class="relative mt-2"><span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] font-bold text-zinc-400">Rp</span><input id="inputNominal" type="number" name="nominal" required min="1000" max="10000000" placeholder="50000" class="w-full bg-zinc-50 border border-zinc-200 rounded-[12px] pl-10 pr-3 py-3 text-[15px] font-bold outline-none focus:border-[#9a0000] min-w-0"></div><div class="flex gap-2 mt-2"><button type="button" onclick="setNominal(20000,this)" class="btn-nominal flex-1 bg-zinc-100 text-[12px] font-bold py-2.5 rounded-full">Rp20.000</button><button type="button" onclick="setNominal(50000,this)" class="btn-nominal flex-1 bg-zinc-100 text-[12px] font-bold py-2.5 rounded-full">Rp50.000</button><button type="button" onclick="setNominal(100000,this)" class="btn-nominal flex-1 bg-zinc-100 text-[12px] font-bold py-2.5 rounded-full">Rp100.000</button></div></div>
            <div><p class="text-[11px] font-bold tracking-widest text-zinc-500">2. METODE PEMBAYARAN</p><div class="grid grid-cols-3 gap-2 mt-2"><label class="cursor-pointer min-w-0"><input type="radio" name="metode_pembayaran" value="cash" class="peer hidden" checked onchange="gantiMetode('cash')"><div class="text-center py-3 rounded-[12px] border-2 border-zinc-200 peer-checked:border-[#9A0000] peer-checked:bg-red-50 peer-checked:text-[#9A0000] font-bold text-[12px]">Tunai</div></label><label class="cursor-pointer min-w-0"><input type="radio" name="metode_pembayaran" value="qris" class="peer hidden" onchange="gantiMetode('qris')"><div class="text-center py-3 rounded-[12px] border-2 border-zinc-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 font-bold text-[12px]">QRIS</div></label><label class="cursor-pointer min-w-0"><input type="radio" name="metode_pembayaran" value="transfer" class="peer hidden" onchange="gantiMetode('transfer')"><div class="text-center py-3 rounded-[12px] border-2 border-zinc-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 font-bold text-[12px]">Transfer</div></label></div><div id="detailCash" class="mt-2 bg-zinc-50 border border-zinc-200 rounded-[12px] p-3 text-[12px] text-zinc-600">Serahkan dana secara langsung kepada bendahara, kemudian unggah bukti pada kolom di bawah.</div><div id="detailQris" class="hidden mt-2 bg-zinc-50 border border-zinc-200 rounded-[12px] p-3 text-center"><img src="{{ asset('qris.jpg') }}" alt="QRIS" class="w-[180px] h-[180px] object-contain mx-auto bg-white border rounded-[10px] p-2"><p class="text-[12px] font-bold mt-2">Pindai kode QRIS di atas</p><p class="text-[11px] text-zinc-500">Atas nama PASKIBRA NESTA</p></div><div id="detailTransfer" class="hidden mt-2 bg-blue-50 border border-blue-200 rounded-[12px] p-3"><p class="text-[11px] text-zinc-500">Transfer ke rekening berikut:</p><p class="font-bold text-[14px] mt-1 break-words">SEABANK - 9012345678912</p><p class="text-[12px]">Atas nama Paskibra Nesta</p><button type="button" onclick="salinRekening()" class="inline-flex mt-2 bg-blue-600 text-white text-[12px] font-bold px-4 py-2 rounded-full">Salin Nomor Rekening</button><p id="salinOk" class="hidden text-[11px] text-emerald-600 font-bold mt-1">Nomor rekening berhasil disalin.</p></div></div>
            <div><p class="text-[11px] font-bold tracking-widest text-zinc-500">3. BUKTI PEMBAYARAN - Wajib Diunggah</p><label class="block mt-2 bg-zinc-50 border-2 border-dashed border-zinc-300 rounded-[12px] p-3 text-center cursor-pointer"><span id="namaBukti" class="text-[12px] font-bold text-zinc-700">Unggah Foto Bukti</span><span class="block text-[11px] text-zinc-500 mt-0.5">Tunai, QRIS, maupun Transfer wajib disertai bukti.</span><input type="file" name="bukti" accept="image/*" required class="hidden" onchange="document.getElementById('namaBukti').textContent='Berkas dipilih: '+this.files[0].name"></label></div>
            <input type="text" name="keterangan" placeholder="Keterangan (opsional)" class="w-full bg-zinc-50 border border-zinc-200 rounded-[12px] px-3 py-3 text-[13px] outline-none focus:border-[#9a0000] min-w-0">
            <button class="w-full text-white font-bold text-[14px] py-3.5 rounded-full bg-[#B91C1C]">Kirim Setoran</button>
            <p class="text-center text-[11px] text-zinc-400">Setoran akan berstatus Pending hingga dilakukan validasi.</p>
        </form>
    </div>
</div>

<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');
const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');document.body.style.overflow='hidden';}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');document.body.style.overflow='';}
openBtn?.addEventListener('click',openMenu);closeBtn?.addEventListener('click',closeMenu);overlay?.addEventListener('click',closeMenu);
function openSetor(){ document.getElementById('modalSetor').classList.remove('hidden'); document.getElementById('modalSetor').classList.add('flex'); document.body.style.overflow='hidden'; }
function closeSetor(){ document.getElementById('modalSetor').classList.add('hidden'); document.getElementById('modalSetor').classList.remove('flex'); document.body.style.overflow=''; }
function setNominal(v, el){ const inp=document.getElementById('inputNominal'); if(inp){ inp.value=v; } document.querySelectorAll('.btn-nominal').forEach(b=>b.classList.remove('is-selected')); if(el) el.classList.add('is-selected'); }
function gantiMetode(m){ document.getElementById('detailCash').classList.toggle('hidden', m!=='cash'); document.getElementById('detailQris').classList.toggle('hidden', m!=='qris'); document.getElementById('detailTransfer').classList.toggle('hidden', m!=='transfer'); }
function salinRekening(){ navigator.clipboard.writeText('9012345678912'); const ok=document.getElementById('salinOk'); ok.classList.remove('hidden'); setTimeout(()=>{ ok.classList.add('hidden'); },2000); }
document.getElementById('modalSetor')?.addEventListener('click', function(e){ if(e.target === this) closeSetor(); });
let saldoHidden=false; const saldoAsli="{{ number_format($myTotalVal,0,',','.') }}";
(function(){ const el=document.getElementById('saldoText'); if(!el) return; const target=parseInt(el.dataset.target||'0',10); const dur=700; const start=performance.now(); function tick(now){ const p=Math.min(1,(now-start)/dur); const eased=1-Math.pow(1-p,3); const val=Math.round(target*eased); el.textContent='Rp'+val.toLocaleString('id-ID'); if(p<1) requestAnimationFrame(tick); else el.textContent='Rp'+saldoAsli; } requestAnimationFrame(tick); })();
function toggleSaldo(){ saldoHidden=!saldoHidden; document.getElementById('saldoText').textContent = saldoHidden ? 'Rp••••••••' : 'Rp'+saldoAsli; }
(function(){
  const card=document.getElementById('mainTierCard'); if(!card) return;
  if(card.dataset.isUpgrade!=='1') return;
  const newTier=card.dataset.newTier;
  const newBg=@json($tierBg), newText=@json($tierText), newGlow=@json($tierGlow), newShine=@json($tierShine), newClass=@json($tierClass);
  const left=document.getElementById('tearLeft'), right=document.getElementById('tearRight'), line=document.getElementById('tearLine');
  const particlesBox=document.getElementById('tierParticles');
  function spawn(n, colors){
    if(!particlesBox) return; particlesBox.innerHTML='';
    for(let i=0;i<n;i++){ const el=document.createElement('i'); const a=Math.random()*Math.PI*2, d=60+Math.random()*120; el.style.left='50%'; el.style.top='40%'; el.style.setProperty('--tx', Math.cos(a)*d+'px'); el.style.setProperty('--ty', Math.sin(a)*d+'px'); el.style.background=colors[i%colors.length]; const s=6+Math.random()*6; el.style.width=s+'px'; el.style.height=s+'px'; el.style.animationDelay=(Math.random()*0.15)+'s'; particlesBox.appendChild(el); }
  }
  setTimeout(()=>{
    const html=card.innerHTML;
    left.innerHTML=html; right.innerHTML=html;
    const oldBg=card.style.background, oldColor=card.style.color;
    [left,right].forEach(el=>{ el.style.background=oldBg; el.style.color=oldColor; el.style.padding='20px'; });
    card.style.visibility='hidden';
    left.classList.add('show'); right.classList.add('show'); line.classList.add('show');
    card.classList.add('is-tearing');
    setTimeout(()=>{
      left.classList.remove('show'); right.classList.remove('show'); line.classList.remove('show');
      card.style.visibility='visible';
      card.style.background=newBg; card.style.color=newText; card.style.boxShadow=newGlow; card.style.setProperty('--tier-shine', newShine);
      card.className=card.className.replace(/tier-(bronze|silver|gold|black)/, newClass);
      document.getElementById('tierBadge').textContent=newTier;
      document.getElementById('tierLabelInline').textContent=newTier;
      document.getElementById('tierBigLabel').textContent=newTier;
      if(newTier==='BLACK'){ card.classList.add('drop-black'); spawn(45, ['#D4AF37','#F6D78B','#fff','#F5E6C4']); }
      else if(newTier==='GOLD'){ card.classList.add('drop-gold'); spawn(28, ['#F6D78B','#D4AF37','#fff']); }
      else { card.classList.add('is-dropping'); spawn(16, ['#fff','#C9CDD1']); }
      setTimeout(()=>{ card.classList.remove('is-tearing'); }, 500);
    }, 1550);
  }, 700);
})();
</script>
</body>
</html>