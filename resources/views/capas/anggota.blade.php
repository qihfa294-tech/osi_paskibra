<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Lihat Anggota - CAPAS - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; -webkit-font-smoothing:antialiased}
.bg-paskibra{background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97);backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border:1px solid rgba(255,255,255,0.2);}
.card-inner{background:white; border:1px solid #f1f1f1; transition:all .22s ease; will-change:transform; transform:translateZ(0)}
.card-inner:hover{transform:translateY(-3px) translateZ(0); box-shadow:0 12px 30px rgba(0,0,0,.11)}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? 'CAPAS';
$namaFullUpper = Str::upper($namaFull);
$namaFirstShort = Str::limit($namaFullUpper, 12, '');
$angkatan = $angkatanFix ?? '2023';
$capas = $capasList ?? collect();
$ppl = $pplList ?? collect();
$pps = $ppsList ?? collect();
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
        <button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">CAPAS MENU</p>
        <div class="space-y-1.5">
          <a href="{{ route('capas.dashboard') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
          @if(isFeatureEnabled('kelola_anggota'))
          <a href="{{ route('capas.anggota') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Lihat Anggota</a>
          @endif
          @if(isFeatureEnabled('absensi'))
          <a href="{{ route('capas.absensi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Absensi</a>
          @endif
          @if(isFeatureEnabled('materi'))
          <a href="{{ route('capas.materi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Materi</a>
          @endif
          @if(isFeatureEnabled('idc'))
          <a href="{{ route('capas.idc') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg>IDC Saya</a>
          @endif
          @if(isFeatureEnabled('e_kas'))
          <a href="{{ route('capas.kas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>E-KAS</a>
          @endif
        </div>
      </div>
      <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
        <p class="text-[10px] font-bold tracking-widest text-amber-300">CAPAS • {{ $angkatan }}</p>
      </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0">
      <div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10">
        <div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ $namaFirstShort }}</p><p class="text-white/40 text-[9px] mt-1">CAPAS • {{ $angkatan }}</p></div></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></button></form>
      </div>
    </div>
  </div>

  <div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen">
    <div class="mb-4">
      <p class="text-white text-[18px] lg:text-[22px] font-[800]">Lihat Anggota</p>
      <p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase">Kamu sebagai CAPAS hanya bisa melihat</p>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-4">
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">CAPAS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $capas->count() }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPL</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $ppl->count() }}</p></div>
      <div class="glass-card rounded-[16px] p-4"><p class="text-[9px] font-bold tracking-widest text-zinc-400">PPS</p><p class="text-[22px] font-[800] mt-1 leading-none">{{ $pps->count() }}</p></div>
    </div>

    {{-- FIX SEARCH: value="" diganti jadi "semua", biar nyambung sama controller --}}
    <form method="GET" action="{{ route('capas.anggota') }}" class="glass-card rounded-[16px] p-3 flex flex-col md:flex-row gap-2 mb-4">
      <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / username..." class="flex-1 px-4 py-2.5 rounded-full border border-zinc-200 text-[12px] outline-none focus:border-[#9a0000] bg-white">
      <select name="angkatan" class="px-4 py-2.5 rounded-full border border-zinc-200 text-[12px] bg-white outline-none">
        <option value="semua" {{ ($filterAngkatan ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Angkatan</option>
        @foreach($listAngkatan ?? [] as $a)<option value="{{ $a }}" {{ (string)($filterAngkatan ?? '') == (string)$a ? 'selected' : '' }}>{{ $a }}</option>@endforeach
      </select>
      <select name="status" class="px-4 py-2.5 rounded-full border border-zinc-200 text-[12px] bg-white outline-none">
        <option value="semua" {{ ($filterStatus ?? 'semua') == 'semua' ? 'selected' : '' }}>Semua Status</option>
        <option value="Aktif" {{ ($filterStatus ?? '') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Non-Aktif" {{ ($filterStatus ?? '') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
      </select>
      <button class="bg-[#9a0000] hover:bg-[#7a0000] text-white px-5 py-2.5 rounded-full text-[12px] font-bold">Cari</button>
      @if(($search ?? '') !== '' || ($filterAngkatan ?? 'semua') !== 'semua' || ($filterStatus ?? 'semua') !== 'semua')
      <a href="{{ route('capas.anggota') }}" class="bg-white text-[#9a0000] border border-red-200 px-5 py-2.5 rounded-full text-[12px] font-bold text-center flex items-center justify-center">Reset</a>
      @endif
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
      <div class="glass-card rounded-[22px] p-4">
        <div class="flex items-center justify-between"><h3 class="font-bold text-[14px]">CAPAS</h3><span class="bg-zinc-100 text-zinc-600 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $capas->count() }} Orang</span></div>
        <div class="space-y-3 mt-4">
          @forelse($capas as $a)
          <div class="card-inner rounded-[16px] p-3">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-500 text-[12px] shrink-0 overflow-hidden">@if($a->foto)<img src="{{ asset('storage/'.$a->foto) }}" class="w-full h-full object-cover">@else {{ strtoupper(substr($a->nama,0,1)) }} @endif</div>
              <div class="min-w-0 flex-1"><p class="font-bold text-[12px] truncate">{{ $a->nama }}</p><p class="text-[10px] text-zinc-500">Angkatan {{ $a->angkatan }} • {{ $a->jabatan }}</p></div>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $a->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-500' }}">{{ $a->status }}</span>
            </div>
          </div>
          @empty <p class="text-center text-zinc-400 text-[12px] py-6">Tidak ada CAPAS.</p> @endforelse
        </div>
      </div>

      <div class="glass-card rounded-[22px] p-4">
        <div class="flex items-center justify-between"><h3 class="font-bold text-[14px]">PPL</h3><span class="bg-zinc-100 text-zinc-600 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $ppl->count() }} Orang</span></div>
        <div class="space-y-3 mt-4">
          @forelse($ppl as $a)
          <div class="card-inner rounded-[16px] p-3">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-500 text-[12px] shrink-0 overflow-hidden">@if($a->foto)<img src="{{ asset('storage/'.$a->foto) }}" class="w-full h-full object-cover">@else {{ strtoupper(substr($a->nama,0,1)) }} @endif</div>
              <div class="min-w-0 flex-1"><p class="font-bold text-[12px] truncate">{{ $a->nama }}</p><p class="text-[10px] text-zinc-500">Angkatan {{ $a->angkatan }} • {{ $a->jabatan }}</p></div>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $a->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-500' }}">{{ $a->status }}</span>
            </div>
          </div>
          @empty <p class="text-center text-zinc-400 text-[12px] py-6">Tidak ada PPL.</p> @endforelse
        </div>
      </div>

      <div class="glass-card rounded-[22px] p-4">
        <div class="flex items-center justify-between"><h3 class="font-bold text-[14px]">PPS</h3><span class="bg-zinc-100 text-zinc-600 text-[10px] font-bold px-2.5 py-1 rounded-full">{{ $pps->count() }} Orang</span></div>
        <div class="space-y-3 mt-4">
          @forelse($pps as $a)
          <div class="card-inner rounded-[16px] p-3">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-500 text-[12px] shrink-0 overflow-hidden">@if($a->foto)<img src="{{ asset('storage/'.$a->foto) }}" class="w-full h-full object-cover">@else {{ strtoupper(substr($a->nama,0,1)) }} @endif</div>
              <div class="min-w-0 flex-1"><p class="font-bold text-[12px] truncate">{{ $a->nama }}</p><p class="text-[10px] text-zinc-500">Angkatan {{ $a->angkatan }} • {{ $a->jabatan }}</p></div>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $a->status == 'Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-500' }}">{{ $a->status }}</span>
            </div>
          </div>
          @empty <p class="text-center text-zinc-400 text-[12px] py-6">Tidak ada PPS.</p> @endforelse
        </div>
      </div>
    </div>
    <div class="mt-auto pt-10 pb-2 text-center"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
  </div>
</div>
<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');document.body.style.overflow='hidden';}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');document.body.style.overflow='';}
openBtn?.addEventListener('click',openMenu);closeBtn?.addEventListener('click',closeMenu);overlay?.addEventListener('click',closeMenu);
</script>
</body>
</html>