<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Edit {{ $anggota->nama }} - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; -webkit-font-smoothing:antialiased}
.bg-paskibra{background:#8a0000;min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.98); border: 1px solid rgba(255,255,255,0.9);}
.input-paskibra{background:#f7f7f8;border:1px solid #eeeeee;transition: all .2s ease;}
.input-paskibra:focus{border-color:#9a0000;box-shadow:0 0 0 4px rgba(154,0,0,0.08);outline:none;background:white;}
.input-paskibra:disabled{background:#f1f5f9;color:#94a3b8;cursor:not-allowed;}
.btn-merah{background:#9a0000;}
.btn-merah:hover{background:#7a0000;}
</style>
</head>
<body class="bg-paskibra relative flex items-center justify-center p-4 lg:p-8">

<div class="w-full max-w-[580px] relative z-10">
    <div class="flex items-center justify-between mb-5 px-2">
        <a href="{{ route('dev.anggota') }}" class="w-10 h-10 rounded-full bg-black/30 backdrop-blur border border-white/10 text-white flex items-center justify-center hover:bg-white hover:text-[#9a0000] transition">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <span class="text-[10px] font-bold tracking-[0.3em] text-white/60 uppercase">Edit • Angkatan {{ $anggota->angkatan ?? $anggota->angkatan_masuk ?? '-' }}</span>
        <div class="w-10 h-10 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-white font-bold text-[12px]">{{ strtoupper(substr($anggota->nama,0,2)) }}</div>
    </div>

    <div class="glass-card rounded-[28px] p-6 lg:p-9">
        <div class="flex justify-between items-start mb-7">
            <div>
                <h1 class="text-[26px] font-[800] tracking-tight text-[#111] leading-[0.9]">Edit<br>Anggota</h1>
                <p class="text-[11px] font-semibold tracking-widest text-zinc-400 uppercase mt-3">ID #{{ $anggota->id }} • {{ $anggota->kategori }}</p>
            </div>
            <div id="badgeKategori" class="bg-[#111] text-white px-4 py-1.5 rounded-full text-[10px] font-bold tracking-widest">{{ $anggota->kategori }}</div>
        </div>

        <form action="{{ route('dev.anggota.update',$anggota->id) }}" method="POST" class="space-y-5" id="formEdit">
            @csrf @method('PUT')

            <div>
                <label class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ $anggota->nama }}" required class="w-full mt-2 px-5 py-4 rounded-full input-paskibra text-[14px] font-semibold text-[#111]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase ml-1">Angkatan</label>
                    <input type="text" name="angkatan" value="{{ $anggota->angkatan ?? $anggota->angkatan_masuk ?? '' }}" required class="w-full mt-2 px-5 py-4 rounded-full input-paskibra text-[14px] font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase ml-1">Status</label>
                    <select name="status" id="status" class="w-full mt-2 px-5 py-4 rounded-full input-paskibra text-[13px] font-bold text-[#9a0000]">
                        <option value="Aktif" {{ $anggota->status=='Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ $anggota->status=='Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="Lulus" {{ $anggota->status=='Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>
            </div>

            <div id="wrapKategoriJabatan" class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase ml-1">Kategori</label>
                    <select name="kategori" id="kat" class="w-full mt-2 px-5 py-4 rounded-full input-paskibra text-[13px] font-bold text-[#111]">
                        @foreach($listKategori as $k)
                        <option value="{{ $k }}" {{ $anggota->kategori==$k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.25em] text-zinc-400 uppercase ml-1">Jabatan</label>
                    <select name="jabatan" id="jab" class="w-full mt-2 px-5 py-4 rounded-full input-paskibra text-[13px] font-bold text-[#9a0000]">
                        @foreach(($listJabatan ?? [$anggota->jabatan]) as $j)
                        <option value="{{ $j }}" {{ $anggota->jabatan==$j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="infoNonAktif" class="hidden items-center gap-3 bg-zinc-50 border border-zinc-200 rounded-[20px] px-5 py-4">
                <span class="w-9 h-9 shrink-0 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </span>
                <div>
                    <p class="text-[12px] font-bold text-zinc-700">Non-Aktif</p>
                    <p class="text-[11px] text-zinc-500 mt-0.5">Kategori dan jabatan disembunyikan karena anggota sedang tidak aktif.</p>
                </div>
            </div>

            <div id="infoLulus" class="hidden items-center gap-3 bg-red-50 border border-red-100 rounded-[20px] px-5 py-4">
                <span class="w-9 h-9 shrink-0 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </span>
                <div>
                    <p class="text-[12px] font-bold text-red-700">Lulus jadi Alumni</p>
                    <p class="text-[11px] text-red-500 mt-0.5">Kategori terkunci ke Alumni, jabatan jadi Anggota.</p>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('dev.anggota') }}" class="flex-1 py-4 rounded-full bg-zinc-100 text-zinc-600 font-bold text-[13px] text-center">Batal</a>
                <button type="submit" class="flex-[2] btn-merah text-white font-bold py-4 rounded-full text-[14px] tracking-wide transition">Simpan</button>
            </div>
            <p class="text-center text-[10px] text-zinc-400 mt-2">Password tidak diubah.</p>
        </form>
    </div>
</div>

<script>
const all = @json($listJabatanAll);
const katEl = document.getElementById('kat');
const jabEl = document.getElementById('jab');
const statusEl = document.getElementById('status');
const wrapKJ = document.getElementById('wrapKategoriJabatan');
const infoNonAktif = document.getElementById('infoNonAktif');
const infoLulus = document.getElementById('infoLulus');
const badgeKategori = document.getElementById('badgeKategori');
const currentJabatan = "{{ $anggota->jabatan }}";
const initialKategori = "{{ $anggota->kategori }}";
let prevKat = initialKategori;
let prevJab = currentJabatan;

function show(el){ el.classList.remove('hidden'); el.classList.add('flex'); }
function hide(el){ el.classList.add('hidden'); el.classList.remove('flex'); }
function setBadge(text, cls){
  badgeKategori.textContent = text;
  badgeKategori.className = cls + ' px-4 py-1.5 rounded-full text-[10px] font-bold tracking-widest';
}
function fillJabatan(kategori, toSelect){
  jabEl.innerHTML = '';
  (all[kategori] || all['CAPAS']).forEach(j=>{
    const opt = document.createElement('option'); opt.value=j; opt.textContent=j;
    if(j===toSelect) opt.selected=true; jabEl.appendChild(opt);
  });
}

function render(){
  const st = statusEl.value;

  if(st === 'Non-Aktif'){
    wrapKJ.classList.add('hidden');
    show(infoNonAktif);
    hide(infoLulus);
    setBadge('Non-Aktif', 'bg-zinc-400 text-white');
    if(katEl.value && katEl.value !== 'ALUMNI') prevKat = katEl.value;
    if(jabEl.value && jabEl.value !== '-') prevJab = jabEl.value;
    katEl.disabled = true;
    jabEl.disabled = true;
    return;
  }

  if(st === 'Lulus'){
    wrapKJ.classList.remove('hidden');
    hide(infoNonAktif);
    show(infoLulus);
    setBadge('ALUMNI', 'bg-[#111] text-white');
    if(katEl.value !== 'ALUMNI'){ prevKat = katEl.value; prevJab = jabEl.value; }
    katEl.value = 'ALUMNI'; katEl.disabled = true;
    jabEl.innerHTML = '<option value="Anggota" selected>Anggota</option>';
    jabEl.disabled = true;
    return;
  }

  // Aktif
  wrapKJ.classList.remove('hidden');
  hide(infoNonAktif);
  hide(infoLulus);
  if(katEl.value === 'ALUMNI' && prevKat !== 'ALUMNI') katEl.value = prevKat;
  if(!katEl.value) katEl.value = prevKat || initialKategori;
  setBadge(katEl.value, 'bg-[#111] text-white');
  katEl.disabled = false; jabEl.disabled = false;
  const kategori = katEl.value;
  const toSelect = (kategori === prevKat) ? prevJab : currentJabatan;
  fillJabatan(kategori, toSelect);
}

statusEl.addEventListener('change', render);
katEl.addEventListener('change', ()=>{
  prevKat = katEl.value;
  prevJab = '';
  setBadge(katEl.value, 'bg-[#111] text-white');
  fillJabatan(katEl.value, '');
});

document.getElementById('formEdit').addEventListener('submit', ()=>{
  katEl.disabled = false;
  jabEl.disabled = false;
  if(statusEl.value === 'Non-Aktif'){
    if(!katEl.value) katEl.value = prevKat || initialKategori;
    jabEl.innerHTML = '<option value="-" selected>-</option>';
  }
  if(statusEl.value === 'Lulus'){
    katEl.value = 'ALUMNI';
    jabEl.innerHTML = '<option value="Anggota" selected>Anggota</option>';
  }
});

render();
</script>
</body>
</html>