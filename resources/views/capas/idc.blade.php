<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IDC Saya - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden}
.bg-paskibra{background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97);backdrop-filter: blur(24px);}
#idcWrapper{width:100%; max-width:820px; overflow:hidden}
#idcCapture{transform-origin:top left; will-change:transform}
@media(max-width:820px){ #idcWrapper{height:calc(100vw * 540 / 856); max-height:540px} }
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? 'CAPAS';
$namaFirst = Str::upper(explode(' ', trim($namaFull))[0]);
$namaFirstShort = Str::limit($namaFirst, 12, '');
$fotoPath = $anggota->foto ?? auth()->user()->foto ?? null;
@endphp

<div class="lg:hidden flex justify-between items-center p-4 sticky top-0 z-20 bg-[#7a0000] border-b border-white/10">
  <div class="flex items-center gap-3"><img src="{{ asset('logo.jpeg') }}" class="w-8 h-8 rounded-full object-cover"><span class="font-[800] italic text-white text-[14px]">OSI</span></div>
  <button id="btnOpen" class="w-10 h-10 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg></button>
</div>
<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">
  <div id="sidebar" class="w-[85%] max-w-[280px] lg:w-[280px] bg-[#4a0000] lg:bg-black/30 backdrop-blur-xl border-r border-white/10 fixed inset-y-0 left-0 z-40 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col h-[100dvh] lg:h-screen">
    <div class="p-7 flex-1 overflow-y-auto no-scrollbar">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3"><img src="{{ asset('logo.jpeg') }}" class="w-10 h-10 rounded-full"><div><h1 class="font-[800] text-white text-[16px] italic leading-none">OSI</h1><p class="text-[10px] font-bold tracking-[0.2em] text-white/60 mt-1">BY PASKIBRA NESTA</p></div></div>
        <button id="btnClose" class="lg:hidden w-8 h-8 rounded-full bg-white/10 text-white flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>
      <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">CAPAS MENU</p>
        <div class="space-y-1.5">
          <a href="{{ route('capas.dashboard') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
          @if(isFeatureEnabled('idc'))
          <a href="{{ route('capas.idc') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg>IDC Saya</a>
          @endif
          @if(isFeatureEnabled('kelola_anggota'))
          <a href="{{ route('capas.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Anggota</a>
          @endif
          @if(isFeatureEnabled('e_kas'))
          <a href="{{ route('capas.kas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>E-KAS</a>
          @endif
          @if(isFeatureEnabled('absensi'))
          <a href="{{ route('capas.absensi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Absensi</a>
          @endif
          @if(isFeatureEnabled('materi'))
          <a href="{{ route('capas.materi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Materi</a>
          @endif
        </div>
      </div>
      <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
        <p class="text-[10px] font-bold tracking-widest text-amber-300">CAPAS • {{ $angkatanFix }}</p>
        <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Akun CAPAS hanya bisa melihat data seangkatan. Tidak bisa tambah / edit / hapus.</p>
      </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0"><div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10"><div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ $namaFirstShort }}</p><p class="text-white/40 text-[9px] mt-1">CAPAS • {{ $angkatanFix }}</p></div></div><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button></form></div></div>
  </div>

  <div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full">
    <div class="mb-6"><p class="text-white text-[22px] font-[800]">IDC Saya</p><p class="text-[11px] tracking-[0.2em] text-white/50 mt-1 uppercase">KARTU IDENTITAS • ANGKATAN {{ $angkatanFix }}</p></div>
    <div class="flex flex-col xl:flex-row gap-5 items-start">
      <div id="idcWrapper" class="w-full">
        <div id="idcCapture" class="relative w-[856px] min-w-[856px] h-[540px] rounded-[20px] overflow-hidden bg-[#7a0000] shadow-2xl flex flex-col">
          <div class="absolute inset-0 bg-gradient-to-br from-[#9e0000] to-[#4a0000]"></div>
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <img src="{{ asset('logo.jpeg') }}" class="w-[720px] h-[720px] rounded-full opacity-[0.07] object-cover">
          </div>
          <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
          <div class="relative z-10 h-[76px] shrink-0 flex justify-between items-center px-8 border-b border-white/10">
            <div class="flex items-center gap-3">
              <img src="{{ asset('logo.jpeg') }}" class="w-[48px] h-[48px] rounded-full border-2 border-white/20">
              <div><p class="text-white font-[800] text-[18px] leading-none tracking-wide">PASKIBRA</p><p class="text-white/70 font-[600] text-[10px] mt-1 tracking-[0.15em]">SMKN 1 TANGERANG</p></div>
            </div>
            <div class="text-right"><p class="text-white/40 text-[8px] font-bold tracking-[0.2em]">LEVEL</p><p class="text-white font-bold text-[13px] mt-0.5 tracking-wide">CAPAS {{ $angkatanFix }}</p></div>
          </div>
          <div class="relative z-10 flex-1 flex items-center px-8 gap-8">
            <div class="w-[184px] shrink-0">
              <div class="w-[184px] h-[230px] rounded-[14px] overflow-hidden bg-black/20 border border-white/15 shadow-lg">
                <div id="fotoIDC" class="w-full h-full {{ $fotoPath ? '' : 'hidden' }}" style="background-image: url('{{ $fotoPath ? asset('storage/'.$fotoPath) : '' }}'); background-size: cover; background-position: top center; background-repeat: no-repeat;"></div>  
                <div id="fotoEmpty" class="w-full h-full flex items-center justify-center {{ $fotoPath ? 'hidden' : '' }}"><svg class="w-10 h-10 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></div>
              </div>
            </div>
            <div class="flex-1 h-[230px] flex flex-col justify-between">
              <div><p class="text-white/40 text-[9px] font-bold tracking-[0.25em]">NAMA LENGKAP</p><p class="text-white text-[24px] font-[800] leading-[1.1] mt-2 uppercase">{{ $namaFull }}</p><div class="mt-4"><p class="text-white/40 text-[9px] font-bold tracking-[0.25em]">ANGKATAN</p><p class="text-white text-[16px] font-bold mt-1">{{ $angkatanFix }}</p></div></div>
              <div class="flex items-end justify-between"><div class="flex items-center gap-2"><div class="w-8 h-[1px] bg-white/30"></div><p class="text-white/30 text-[8px] tracking-[0.2em]">PASKIBRA NESTA</p></div><div id="qrcode" class="bg-white p-2 rounded-[10px] shadow-xl translate-y-2"></div></div>
            </div>
          </div>
          <div class="absolute bottom-0 left-0 right-0 h-[4px] bg-white/15 z-10"></div>
        </div>
      </div>
      <div class="w-full xl:w-[320px] space-y-3 mt-4 xl:mt-0">
        <div class="glass-card rounded-[16px] p-4"><p class="text-[11px] font-bold tracking-widest text-zinc-800">EDIT FOTO IDC</p><p class="text-[11px] text-zinc-500 mt-1">Max 2MB.</p><input type="file" id="inputFoto" accept="image/*" class="hidden"><button onclick="document.getElementById('inputFoto').click()" class="mt-4 w-full bg-[#9a0000] text-white px-4 py-3 rounded-full text-[12px] font-bold">Pilih Foto Baru</button><div id="statusUpload" class="hidden mt-3 text-[11px] p-2 rounded-lg"></div></div>
        <button onclick="downloadIDC()" class="w-full bg-white px-4 py-3 rounded-full text-[12px] font-bold text-[#9a0000] shadow-md">Download IDC</button>
      </div>
    </div>
  </div>
</div>

<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');const openBtn=document.getElementById('btnOpen');const closeBtn=document.getElementById('btnClose');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');}
if(openBtn) openBtn.addEventListener('click',openMenu);
if(closeBtn) closeBtn.addEventListener('click',closeMenu);
if(overlay) overlay.addEventListener('click',closeMenu);

function scaleIDC(){
  const wrapper=document.getElementById('idcWrapper');
  const card=document.getElementById('idcCapture');
  if(!wrapper||!card) return;
  const wrapperWidth=wrapper.clientWidth;
  const scale=wrapperWidth<856 ? wrapperWidth/856 : 1;
  card.style.transform=`scale(${scale})`;
  wrapper.style.height = wrapperWidth<856 ? (540*scale)+'px' : '540px';
}
window.addEventListener('load',scaleIDC);
window.addEventListener('resize',scaleIDC);

const qrText = "OSI-{{ strtoupper(auth()->user()->username) }}-{{ $angkatanFix }}-{{ auth()->user()->id }}";
new QRCode(document.getElementById("qrcode"), {text: qrText, width: 110, height: 110, correctLevel: QRCode.CorrectLevel.H});

async function downloadIDC(){
  const W=856, H=540, SCALE=3;
  const canvas=document.createElement('canvas');
  canvas.width=W*SCALE; canvas.height=H*SCALE;
  const ctx=canvas.getContext('2d');
  ctx.scale(SCALE,SCALE);

  const logoUrl="{{ asset('logo.jpeg') }}";
  const loadImg = (src)=> new Promise(res=>{ const im=new Image(); im.crossOrigin='anonymous'; im.onload=()=>res(im); im.onerror=()=>res(null); im.src=src; });

  const grad=ctx.createLinearGradient(0,0,W,H);
  grad.addColorStop(0,'#9e0000'); grad.addColorStop(1,'#4a0000');
  ctx.fillStyle=grad; ctx.fillRect(0,0,W,H);
  ctx.fillStyle='rgba(0,0,0,0.1)'; ctx.fillRect(0,0,W,H);

  try{
    const logoBig=await loadImg(logoUrl);
    if(logoBig){
      ctx.save();
      ctx.globalAlpha=0.07;
      const size=720;
      const x=(W-size)/2;
      const y=(H-size)/2;
      ctx.beginPath();
      ctx.arc(x+size/2, y+size/2, size/2, 0, Math.PI*2);
      ctx.clip();
      ctx.drawImage(logoBig, x, y, size, size);
      ctx.restore();
    }
  }catch(e){}

  ctx.fillStyle='rgba(255,255,255,0.1)'; ctx.fillRect(0,76,W,1);
  ctx.fillStyle='rgba(255,255,255,0.15)'; ctx.fillRect(0,H-4,W,4);

  try{
    const logoSmall=await loadImg(logoUrl);
    if(logoSmall){
      ctx.save();
      ctx.beginPath();
      ctx.arc(32+24, 14+24, 24, 0, Math.PI*2);
      ctx.clip();
      ctx.drawImage(logoSmall, 32, 14, 48, 48);
      ctx.restore();
      ctx.strokeStyle='rgba(255,255,255,0.2)'; ctx.lineWidth=2;
      ctx.beginPath(); ctx.arc(32+24, 14+24, 24, 0, Math.PI*2); ctx.stroke();
    }
  }catch(e){}

  const fotoEl=document.getElementById('fotoIDC');
  let fotoUrl = fotoEl.style.backgroundImage.slice(5,-2).replace(/"/g,'');
  if(fotoUrl){
    try{
      let img=await loadImg(fotoUrl);
      if(!img){
        const r=await fetch(fotoUrl); const b=await r.blob();
        const dataUrl=await new Promise(r=>{ const fr=new FileReader(); fr.onload=()=>r(fr.result); fr.readAsDataURL(b); });
        img=await loadImg(dataUrl);
      }
      if(img){
        const fx=32, fy=76+117, fw=184, fh=230, r=14;
        ctx.save();
        ctx.beginPath(); ctx.roundRect(fx,fy,fw,fh,r); ctx.clip();
        const ir=img.width/img.height, br=fw/fh;
        let sx,sy,sw,sh;
        if(ir>br){ sh=img.height; sw=sh*br; sx=(img.width-sw)/2; sy=0; }
        else { sw=img.width; sh=sw/br; sx=0; sy=0; }
        ctx.drawImage(img,sx,sy,sw,sh,fx,fy,fw,fh);
        ctx.restore();
        ctx.strokeStyle='rgba(255,255,255,0.15)'; ctx.lineWidth=1; ctx.beginPath(); ctx.roundRect(fx,fy,fw,fh,r); ctx.stroke();
      }
    }catch(e){}
  }

  ctx.fillStyle='rgba(255,255,255,0.4)'; ctx.font='700 9px "Plus Jakarta Sans"'; ctx.fillText('NAMA LENGKAP', 250, 195);
  ctx.fillStyle='#fff'; 
  let nama="{{ $namaFull }}";
  let fontSize=26;
  ctx.font=`800 ${fontSize}px "Plus Jakarta Sans"`;
  while(ctx.measureText(nama.toUpperCase()).width>500 && fontSize>14){ fontSize--; ctx.font=`800 ${fontSize}px "Plus Jakarta Sans"`; }
  ctx.fillText(nama.toUpperCase(), 250, 225);
  ctx.fillStyle='rgba(255,255,255,0.4)'; ctx.font='700 9px "Plus Jakarta Sans"'; ctx.fillText('ANGKATAN', 250, 260);
  ctx.fillStyle='#fff'; ctx.font='700 16px "Plus Jakarta Sans"'; ctx.fillText('{{ $angkatanFix }}', 250, 282);
  ctx.fillStyle='rgba(255,255,255,0.3)'; ctx.fillRect(250,410,32,1); ctx.font='400 8px "Plus Jakarta Sans"'; ctx.fillText('PASKIBRA NESTA', 290, 413);
  ctx.fillStyle='#fff'; ctx.font='800 18px "Plus Jakarta Sans"'; ctx.fillText('PASKIBRA', 88, 40);
  ctx.fillStyle='rgba(255,255,255,0.7)'; ctx.font='600 10px "Plus Jakarta Sans"'; ctx.fillText('SMKN 1 TANGERANG', 88, 54);
  ctx.fillStyle='rgba(255,255,255,0.4)'; ctx.font='700 8px "Plus Jakarta Sans"'; ctx.fillText('LEVEL', 780, 30);
  ctx.fillStyle='#fff'; ctx.font='700 13px "Plus Jakarta Sans"'; ctx.textAlign='right'; ctx.fillText('CAPAS {{ $angkatanFix }}', 824, 48); ctx.textAlign='left';

  const qrCanvas=document.querySelector('#qrcode canvas') || document.querySelector('#qrcode img');
  if(qrCanvas){
    const qx=856-32-110-2, qy=193+230-110+8, qs=110;
    ctx.fillStyle='#fff'; ctx.beginPath(); ctx.roundRect(qx-8,qy-8,qs+16,qs+16,10); ctx.fill();
    if(qrCanvas.tagName==='CANVAS') ctx.drawImage(qrCanvas, qx, qy, qs, qs);
    else { const qi=new Image(); qi.src=qrCanvas.src; await new Promise(r=>{ qi.onload=r; }); ctx.drawImage(qi,qx,qy,qs,qs); }
  }

  const a=document.createElement('a');
  a.download=`idc_{{ strtolower(str_replace(' ','_', Str::limit($namaFull, 25, ''))) }}.jpg`;
  a.href=canvas.toDataURL('image/jpeg',1.0);
  a.click();
}

const inputFoto=document.getElementById('inputFoto');const fotoIDC=document.getElementById('fotoIDC');const fotoEmpty=document.getElementById('fotoEmpty');const statusUpload=document.getElementById('statusUpload');
inputFoto.addEventListener('change', async function(e){
  const file=e.target.files[0]; if(!file) return;
  const reader=new FileReader();
  reader.onload=function(ev){ fotoIDC.style.backgroundImage = `url('${ev.target.result}')`; fotoIDC.classList.remove('hidden'); if(fotoEmpty) fotoEmpty.classList.add('hidden'); }
  reader.readAsDataURL(file);
  statusUpload.classList.remove('hidden'); statusUpload.className="mt-3 text-[11px] p-2 rounded-lg bg-blue-50 text-blue-600"; statusUpload.innerText="Mengupload...";
  const formData=new FormData(); 
  let finalFile=file;
  if(file.size>1024*1024){
    try{
      const bmp=await createImageBitmap(file);
      const c=document.createElement('canvas'); c.width=800; c.height=bmp.height*(800/bmp.width);
      c.getContext('2d').drawImage(bmp,0,0,c.width,c.height);
      const blob=await new Promise(r=>c.toBlob(r,'image/jpeg',0.8));
      finalFile=new File([blob], file.name.replace(/\.\w+$/, '.jpg'), {type:'image/jpeg'});
    }catch(e){}
  }
  formData.append('foto',finalFile); formData.append('_token','{{ csrf_token() }}');
  try{
    const res=await fetch("{{ route('capas.idc.foto') }}",{method:'POST',body:formData});
    const data=await res.json();
    if(data.success){ statusUpload.className="mt-3 text-[11px] p-2 rounded-lg bg-green-50 text-green-600"; statusUpload.innerText="Foto berhasil disimpan!"; setTimeout(()=>statusUpload.classList.add('hidden'),3000); }
    else throw new Error();
  }catch(err){ statusUpload.className="mt-3 text-[11px] p-2 rounded-lg bg-red-50 text-red-600"; statusUpload.innerText="Gagal upload, max 2MB"; }
});
</script>
</body>
</html>