<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Kelola Anggota - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; -webkit-font-smoothing:antialiased}
.bg-paskibra{background:#8a0000;min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97); border:1px solid rgba(255,255,255,0.2);}
.card-inner{background:white; border:1px solid #f1f1f1; transition:all .2s ease}
.card-inner:hover{border-color:#e5e5e5; box-shadow:0 8px 20px rgba(0,0,0,.06)}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
.input-paskibra{background:#f7f7f8; border:1px solid #eeeeee;}
.input-paskibra:focus{background:white; border-color:#9a0000; outline:none;}
@keyframes notifShrink { from { transform: scaleX(1); } to { transform: scaleX(0); } }
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = auth()->user()->username ?? 'Developer';
$namaFirstShort = Str::limit(Str::upper($namaFull), 12, '');
$all = $anggotas ?? collect();
$filterKategori = $kategori ?? request('kategori','Semua');
if($filterKategori === 'CAPAS' && !request()->has('kategori')){
    $filterKategori = 'Semua';
}
$cols = [
  ['key'=>'CAPAS','label'=>'CAPAS','list'=>$all->where('kategori','CAPAS')->values()],
  ['key'=>'PPL','label'=>'PPL','list'=>$all->where('kategori','PPL')->values()],
  ['key'=>'PPS','label'=>'PPS','list'=>$all->where('kategori','PPS')->values()],
  ['key'=>'ALUMNI','label'=>'ALUMNI','list'=>$all->where('kategori','ALUMNI')->values()],
  ['key'=>'DEVELOPER','label'=>'DEVELOPER','list'=>$all->where('kategori','DEVELOPER')->values()],
];
$shown = $filterKategori === 'Semua' ? $cols : array_values(array_filter($cols, fn($c)=>$c['key']===$filterKategori));
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
        <div class="flex items-center gap-3"><img src="{{ asset('logo_osi.png') }}" class="w-10 h-10 object-contain"><div><h1 class="font-[800] text-white text-[16px] italic leading-none">OSI</h1><p class="text-[10px] font-bold tracking-[0.2em] text-white/60 mt-1">BY PASKIBRA NESTA</p></div></div>
        <button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">MAIN MENU</p>
        <div class="space-y-1.5">
          <a href="{{ route('dev.dashboard') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Dashboard</a>
          <a href="{{ route('dev.anggota') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow">Kelola Anggota</a>
          <a href="{{ route('dev.validasiKas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">E-KAS</a>
          <a href="{{ route('dev.logsSaldo') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]">Logs Saldo</a>
        </div>
      </div>
      <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
        <p class="text-[10px] font-bold tracking-widest text-amber-300">DEVELOPER • {{ $filterAngkatan ?? 'All' }}</p>
        <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Pilih kategori di atas biar gak pusing. Centang buat luluskan massal.</p>
      </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0">
      <div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10">
        <div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr(auth()->user()->username,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ $namaFirstShort }}</p><p class="text-white/40 text-[9px] mt-1">Developer • {{ $filterAngkatan ?? 'All' }}</p></div></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form>
      </div>
    </div>
  </div>

  <div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen max-w-[900px] mx-auto">
    <div class="mb-4">
      <p class="text-white text-[22px] font-[800]">Kelola Anggota</p>
      <p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase">{{ $filterAngkatan ?? 'Semua Angkatan' }} • {{ $all->count() }} Orang</p>
    </div>

    @if(session('success') || session('error'))
    <div class="rounded-[14px] px-4 py-3 mb-4 {{ session('error') ? 'bg-[#FFF1F1]' : 'bg-emerald-50' }}"><p class="text-[12px] font-bold {{ session('error') ? 'text-[#7a0000]' : 'text-emerald-800' }}">{{ session('success') ?? session('error') }}</p></div>
    @endif

    {{-- Filter kategori - ini pengganti 5 kolom, jauh lebih adem --}}
    <div class="flex gap-2 mb-4 overflow-auto no-scrollbar pb-1">
      @foreach(array_merge([['key'=>'Semua','label'=>'Semua']], $cols) as $t)
      <a href="{{ route('dev.anggota') }}?kategori={{ $t['key'] }}{{ $filterAngkatan ? '&angkatan='.$filterAngkatan : '' }}{{ !empty($search) ? '&search='.urlencode($search) : '' }}" class="whitespace-nowrap px-5 py-2.5 rounded-full text-[12px] font-bold transition {{ $filterKategori==$t['key'] ? 'bg-white text-[#9a0000] shadow' : 'bg-black/20 text-white/70 border border-white/10 hover:bg-white/10 hover:text-white' }}">{{ $t['label'] }} @if($t['key']!='Semua') ({{ $all->where('kategori',$t['key'])->count() }}) @endif</a>
      @endforeach
    </div>

    <form method="GET" action="{{ route('dev.anggota') }}" class="glass-card rounded-full p-1.5 pl-5 pr-1.5 flex items-center gap-2 mb-4">
      <input type="hidden" name="kategori" value="{{ $filterKategori }}">
      @if($filterAngkatan)<input type="hidden" name="angkatan" value="{{ $filterAngkatan }}">@endif
      <svg class="text-zinc-400 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, angkatan, jabatan..." class="flex-1 bg-transparent outline-none text-[13px] font-bold placeholder:text-zinc-400 placeholder:font-normal min-w-0">
      <button class="shrink-0 bg-[#9a0000] text-white px-5 py-2.5 rounded-full text-[12px] font-bold">Cari</button>
    </form>

    <div class="glass-card rounded-[22px] p-4 mb-4">
      <div class="flex flex-col sm:flex-row gap-3">
        <form action="{{ route('dev.anggota.promosiAngkatan') }}" method="POST" class="flex-1 flex gap-2">
          @csrf
          <select name="angkatan" required class="flex-1 px-4 py-3 rounded-full input-paskibra text-[12px] font-bold"><option value="">Angkatan</option>@foreach($listAngkatan as $akt)<option value="{{ $akt }}" {{ $filterAngkatan==$akt?'selected':'' }}>{{ $akt }}</option>@endforeach</select>
          <select name="ke_kategori" required class="flex-1 px-4 py-3 rounded-full bg-[#111] text-white text-[12px] font-bold"><option value="PPL">Ke PPL</option><option value="PPS">Ke PPS</option><option value="ALUMNI">Ke Alumni</option><option value="CAPAS">Ke CAPAS</option><option value="DEVELOPER">Ke Developer</option></select>
          <button class="bg-[#9a0000] text-white px-5 py-3 rounded-full font-bold text-[12px] shrink-0">Pindahkan</button>
        </form>
        <button id="btnBulk" class="hidden bg-[#111] text-white px-5 py-3 rounded-full text-[12px] font-bold shrink-0"><span id="countSelected">0 dipilih</span> • Luluskan</button>
      </div>
      <p class="text-[11px] text-zinc-400 mt-2">Pindah Angkatan = pindah 1 angkatan sekaligus. Luluskan = centang di bawah dulu.</p>
    </div>

    @foreach($shown as $c)
    <div class="glass-card rounded-[22px] p-5 mb-4">
      <div class="flex items-center justify-between">
        <h3 class="font-bold text-[15px]">{{ $c['label'] }} <span class="text-zinc-400 font-normal text-[12px]">• {{ $c['list']->count() }} orang</span></h3>
        <label class="flex items-center gap-2 text-[11px] font-bold cursor-pointer"><input type="checkbox" class="check-all-col w-4 h-4 accent-[#9a0000]" data-col="{{ $c['key'] }}"> Pilih Semua</label>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
        @forelse($c['list'] as $a)
        <div class="card-inner rounded-[16px] p-4 flex items-center gap-3 {{ ($a->status ?? 'Aktif') === 'Non-Aktif' ? 'opacity-60' : '' }}">
          <input type="checkbox" class="check-anggota w-4 h-4 accent-[#9a0000] shrink-0" data-col="{{ $c['key'] }}" value="{{ $a->id }}">
          <div class="w-10 h-10 rounded-full {{ ($a->status ?? 'Aktif') === 'Non-Aktif' ? 'bg-zinc-400' : 'bg-[#9a0000]' }} text-white flex items-center justify-center font-bold text-[12px] shrink-0">{{ strtoupper(substr($a->nama_lengkap ?? $a->nama,0,2)) }}</div>
          <div class="min-w-0 flex-1">
            <p class="font-bold text-[13px] truncate">{{ $a->nama_lengkap ?? $a->nama }}</p>
            <p class="text-[11px] text-zinc-500">{{ ($a->status ?? 'Aktif') === 'Non-Aktif' ? 'Non-Aktif' : $a->jabatan }} • {{ $a->angkatan }} • <span class="{{ ($a->status ?? 'Aktif')==='Aktif' ? 'text-emerald-600' : 'text-zinc-500' }}">{{ $a->status ?? 'Aktif' }}</span></p>
          </div>
          <div class="flex gap-1.5 shrink-0">
            <a href="{{ route('dev.anggota.edit', $a->id) }}" class="bg-[#111] text-white px-3.5 py-2 rounded-full text-[11px] font-bold">Edit</a>
            <form action="{{ route('dev.anggota.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $a->nama_lengkap ?? $a->nama }}?')">@csrf @method('DELETE')<button class="bg-red-50 text-red-600 px-3.5 py-2 rounded-full text-[11px] font-bold">Hapus</button></form>
          </div>
        </div>
        @empty <p class="text-zinc-400 text-[12px] col-span-2 py-4 text-center">Belum ada {{ $c['label'] }}.</p> @endforelse
      </div>
    </div>
    @endforeach

    <div class="mt-auto pt-10 pb-2 text-center"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
  </div>
</div>
<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');
const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');}
openBtn?.addEventListener('click',openMenu);closeBtn?.addEventListener('click',closeMenu);overlay?.addEventListener('click',closeMenu);
const countEl=document.getElementById('countSelected');const btnBulk=document.getElementById('btnBulk');
function updateCount(){
  const c=[...document.querySelectorAll('.check-anggota')].filter(cb=>cb.checked).length;
  if(countEl) countEl.textContent=c+' dipilih';
  if(btnBulk) btnBulk.classList.toggle('hidden',c===0);
}
document.querySelectorAll('.check-all-col').forEach(cb=>{cb.addEventListener('change',()=>{document.querySelectorAll('.check-anggota[data-col="'+cb.dataset.col+'"]').forEach(x=>x.checked=cb.checked);updateCount();});});
document.addEventListener('change',e=>{if(e.target.classList.contains('check-anggota')) updateCount()});
btnBulk?.addEventListener('click',()=>{
  const ids=[...document.querySelectorAll('.check-anggota')].filter(cb=>cb.checked).map(cb=>cb.value);
  if(!ids.length) return alert('Pilih dulu');
  const f=document.createElement('form'); f.method='POST'; f.action="{{ route('dev.anggota.bulkUpdate') }}";
  f.innerHTML=`<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="aksi" value="luluskan">`+ids.map(id=>`<input type="hidden" name="ids[]" value="${id}">`).join('');
  document.body.appendChild(f); f.submit();
});
</script>
</body>
</html>