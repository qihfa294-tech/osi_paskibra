<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>IDC Saya - PPS - OSI</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; -webkit-font-smoothing:antialiased}
.bg-paskibra{background: radial-gradient(120% 120% at 0% 0%, #b80000 0%, #7a0000 38%, #0a0a0a 100%);min-height:100vh;}
.glass-card{background: rgba(255,255,255,0.97);backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border:1px solid rgba(255,255,255,0.2);}
#idcWrapper{width:100%; overflow:hidden; position:relative}
#idcCapture{transform-origin:top left; will-change:transform; width:856px; height:540px; flex-shrink:0}
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none; scrollbar-width:none}
.shine{position:absolute; inset:0; background:linear-gradient(105deg, transparent 20%, rgba(255,255,255,.08) 35%, rgba(255,255,255,.22) 50%, transparent 65%); transform:translateX(-120%); transition:transform .9s ease; pointer-events:none; z-index:30}
#idcWrapper:hover .shine{transform:translateX(120%)}
@media (max-width:640px){ .shine{display:none} }
</style>
</head>
<body class="bg-paskibra">
@php
use Illuminate\Support\Str;
$namaFull = $namaLengkap ?? $anggota->nama ?? auth()->user()->username ?? 'PPS';
$namaFullUpper = Str::upper($namaFull);
$angkatan = $angkatanFix ?? $anggota->angkatan ?? auth()->user()->angkatan_masuk ?? '2023';
$fotoPath = $foto ?? $anggota->foto ?? null;
@endphp

<!-- TOAST NOTIF CAKep -->
<div id="toast" class="fixed bottom-5 left-1/2 -translate-x-1/2 z-[100] hidden">
  <div class="bg-zinc-900 text-white px-5 py-3 rounded-full shadow-[0_10px_30px_rgba(0,0,0,.4)] flex items-center gap-3 text-[12px] font-bold">
    <div id="toastIcon" class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center">✓</div>
    <p id="toastText">Foto berhasil disimpan!</p>
  </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[90] hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-[22px] p-5 w-full max-w-[360px] shadow-2xl">
    <div class="w-full h-[200px] rounded-[14px] overflow-hidden bg-zinc-100"><div id="previewModal" class="w-full h-full bg-cover bg-top"></div></div>
    <h3 class="font-[800] text-[15px] mt-4">Simpan foto ini?</h3>
    <p class="text-[11px] text-zinc-500 mt-1">Foto akan dipakai untuk IDC PPS kamu. Yakin mau simpan?</p>
    <div class="grid grid-cols-2 gap-2.5 mt-5">
      <button id="btnCancel" class="w-full bg-zinc-100 hover:bg-zinc-200 text-zinc-800 py-3 rounded-full text-[12px] font-bold">Batal</button>
      <button id="btnConfirm" class="w-full bg-[#9a0000] hover:bg-[#7a0000] text-white py-3 rounded-full text-[12px] font-bold">Ya, Simpan</button>
    </div>
  </div>
</div>

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
    <div class="mt-10"><p class="text-[10px] font-bold tracking-[0.3em] text-white/30 mb-4">PPS MENU</p>
        <div class="space-y-1.5">
        <a href="{{ route('pps.dashboard') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px] transition"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</a>
        @if(isFeatureEnabled('idc'))<a href="{{ route('pps.idc') }}" class="flex items-center gap-3 bg-white text-[#9a0000] px-4 py-3 rounded-full font-bold text-[13px] shadow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="10" r="2"/></svg>IDC Saya</a>@endif
        @if(isFeatureEnabled('kelola_anggota'))<a href="{{ route('pps.anggota') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Kelola Anggota</a>@endif
        @if(isFeatureEnabled('e_kas'))<a href="{{ route('pps.ekas') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>Kelola Kas</a>@endif
        @if(isFeatureEnabled('e_inventaris'))<a href="{{ route('pps.inventaris') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>Inventaris</a>@endif
        @if(isFeatureEnabled('absensi'))<a href="{{ route('pps.absensi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Absensi</a>@endif
        @if(isFeatureEnabled('materi'))<a href="{{ route('pps.materi') }}" class="flex items-center gap-3 text-white/60 hover:text-white hover:bg-white/10 px-4 py-3 rounded-full text-[13px]"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Materi</a>@endif
        </div>
    </div>
    <div class="mt-8 bg-white/5 border border-white/10 rounded-[16px] p-4">
      <p class="text-[10px] font-bold tracking-widest text-amber-300">PPS • {{ $angkatan }}</p>
      <p class="text-[11px] text-white/60 mt-2 leading-relaxed">Akun PPS bisa kelola anggota, kas, inventaris, absensi & materi.</p>
    </div>
    </div>
    <div class="p-4 border-t border-white/10 bg-black/30 shrink-0">
    <div class="flex items-center justify-between bg-white/5 rounded-2xl p-3 border border-white/10">
        <div class="flex items-center gap-3 min-w-0"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#9a0000] font-bold text-[12px] shrink-0">{{ strtoupper(substr($namaFull,0,1)) }}</div><div class="min-w-0"><p class="text-white text-[11px] font-semibold leading-none truncate">{{ Str::limit($namaFull, 18) }}</p><p class="text-white/40 text-[9px] mt-1">PPS • {{ $angkatan }}</p></div></div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-8 h-8 rounded-full bg-white/10 hover:bg-red-600 flex items-center justify-center text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></button></form>
    </div>
    </div>
</div>

<div class="flex-1 lg:ml-[280px] p-4 lg:p-8 w-full flex flex-col min-h-screen overflow-hidden">
    <div class="flex-1 w-full min-w-0">
      <div class="mb-6">
        <p class="text-white text-[18px] lg:text-[22px] font-[800] leading-tight">IDC Saya - {{ $namaFullUpper }}</p>
        <p class="text-[11px] tracking-[0.15em] text-white/50 mt-1 uppercase">KARTU IDENTITAS • PPS • ANGKATAN {{ $angkatan }}</p>
      </div>
      <div class="grid grid-cols-1 xl:grid-cols-[1fr_340px] gap-6 items-start w-full min-w-0">
        <div class="w-full min-w-0">
          <div id="idcWrapper" class="w-full">
            <div id="idcCapture" class="relative rounded-[20px] lg:rounded-[24px] overflow-hidden bg-[#6d0000] shadow-2xl flex flex-col">
              <div class="absolute inset-0 bg-gradient-to-br from-[#8a0000] via-[#6d0000] to-[#4a0000]"></div>
              <div class="absolute inset-0 flex items-center justify-center pointer-events-none"><img src="{{ asset('logo.jpeg') }}" class="w-[820px] h-[820px] rounded-full opacity-[0.06] object-cover"></div>
              <div class="shine"></div>
              <div class="relative z-10 h-[82px] shrink-0 flex justify-between items-center px-8 border-b border-white/[0.08]">
                <div class="flex items-center gap-3.5"><img src="{{ asset('logo.jpeg') }}" class="w-[52px] h-[52px] rounded-full border-2 border-white/20 object-cover"><div><p class="text-white font-[800] text-[20px] leading-none tracking-wide">PASKIBRA</p><p class="text-white/70 font-[700] text-[11px] mt-1 tracking-[0.1em]">SMKN 1 TANGERANG</p></div></div>
                <div class="text-right"><p class="text-white/40 text-[9px] font-bold tracking-[0.2em]">LEVEL</p><p class="text-white font-bold text-[15px] mt-0.5">PPS {{ $angkatan }}</p></div>
              </div>
              <div class="relative z-10 flex-1 flex items-center px-10 gap-10">
                <div class="w-[200px] shrink-0"><div class="w-[200px] h-[280px] rounded-[18px] overflow-hidden bg-black/30 border border-white/10"><div id="fotoIDC" class="w-full h-full {{ $fotoPath ? '' : 'hidden' }}" style="background-image: url('{{ $fotoPath ? asset('storage/'.$fotoPath) : '' }}'); background-size: cover; background-position: top center;"></div><div id="fotoEmpty" class="w-full h-full flex items-center justify-center {{ $fotoPath ? 'hidden' : '' }}"><svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></div></div></div>
                <div class="flex-1 h-[280px] flex flex-col justify-between py-2"><div><p class="text-white/40 text-[10px] font-bold tracking-[0.2em]">NAMA LENGKAP</p><p class="text-white text-[26px] font-[800] leading-[1.05] mt-2 uppercase break-words">{{ $namaFull }}</p><div class="mt-6"><p class="text-white/40 text-[10px] font-bold tracking-[0.2em]">ANGKATAN</p><p class="text-white text-[18px] font-bold mt-1">{{ $angkatan }}</p></div></div><div class="flex items-end justify-between"><div class="flex items-center gap-3"><div class="w-[48px] h-[1px] bg-white/30"></div><p class="text-white/30 text-[9px] tracking-[0.2em]">PASKIBRA NESTA</p></div><div id="qrcode" class="bg-white p-2.5 rounded-[14px] shadow-2xl"></div></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full xl:w-[340px] shrink-0 space-y-3">
          <div id="dropArea" class="glass-card rounded-[16px] p-4 border-2 border-dashed border-zinc-200 hover:border-[#9a0000]/30">
            <p class="text-[11px] font-bold tracking-widest text-zinc-800">EDIT FOTO IDC</p><p class="text-[11px] text-zinc-500 mt-1">Tap untuk pilih foto • {{ Str::limit($namaFull, 22) }}</p>
            <input type="file" id="inputFoto" accept="image/*" class="hidden"><button class="mt-4 w-full bg-[#9a0000] hover:bg-[#7a0000] text-white px-4 py-3 rounded-full text-[12px] font-bold transition">Pilih Foto Baru</button>
          </div>
          <button onclick="downloadIDC()" class="w-full bg-white px-4 py-3.5 rounded-full text-[12px] font-bold text-[#9a0000] shadow-md">Download IDC (HD)</button>
          <a href="{{ route('pps.dashboard') }}" class="block w-full text-center bg-white/10 border border-white/10 text-white px-4 py-3 rounded-full text-[12px] font-bold hover:bg-white/15 transition">Kembali ke Dashboard</a>
        </div>
      </div>
    </div>
    <div class="pt-12 pb-6 text-center"><p class="text-[9px] tracking-[0.2em] text-white/40">PASKIBRA NESTA © 2026</p></div>
</div>
</div>

<script>
const sidebar=document.getElementById('sidebar');const overlay=document.getElementById('overlay');
function openMenu(){sidebar.classList.remove('-translate-x-full');overlay.classList.remove('hidden');document.body.style.overflow='hidden';}
function closeMenu(){sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden');document.body.style.overflow='';}
document.getElementById('btnOpen')?.addEventListener('click',openMenu);
document.getElementById('btnClose')?.addEventListener('click',closeMenu);
overlay?.addEventListener('click',closeMenu);
function scaleIDC(){const w=document.getElementById('idcWrapper');const c=document.getElementById('idcCapture');if(!w||!c) return;const avail=w.clientWidth;if(avail<=0) return;const s=Math.min(avail/856,1);c.style.transform=`scale(${s})`;w.style.height=(540*s)+'px';}
window.addEventListener('load',scaleIDC);window.addEventListener('resize',scaleIDC);setTimeout(scaleIDC,100);setTimeout(scaleIDC,500);
new QRCode(document.getElementById("qrcode"), {text: "OSI-PPS-{{ Str::slug($namaFull, '') }}-{{ $angkatan }}-{{ auth()->id() }}", width: 96, height: 96});

async function downloadIDC(){const W=856,H=540,SCALE=3,R=24;const canvas=document.createElement('canvas');canvas.width=W*SCALE;canvas.height=H*SCALE;const ctx=canvas.getContext('2d');ctx.scale(SCALE,SCALE);const logoUrl="{{ asset('logo.jpeg') }}";const loadImg=(src)=>new Promise(res=>{const im=new Image();im.crossOrigin='anonymous';im.onload=()=>res(im);im.onerror=()=>res(null);im.src=src;});ctx.save();ctx.beginPath();ctx.roundRect(0,0,W,H,R);ctx.clip();const grad=ctx.createLinearGradient(0,0,W,H);grad.addColorStop(0,'#8a0000');grad.addColorStop(1,'#4a0000');ctx.fillStyle=grad;ctx.fillRect(0,0,W,H);try{const logoBig=await loadImg(logoUrl);if(logoBig){ctx.save();ctx.globalAlpha=0.06;const size=820;const x=(W-size)/2;const y=(H-size)/2;ctx.beginPath();ctx.arc(x+size/2,y+size/2,size/2,0,Math.PI*2);ctx.clip();ctx.drawImage(logoBig,x,y,size,size);ctx.restore();}}catch(e){}ctx.fillStyle='rgba(255,255,255,0.08)';ctx.fillRect(0,82,W,1);try{const logoSmall=await loadImg(logoUrl);if(logoSmall){ctx.save();ctx.beginPath();ctx.arc(58,41,26,0,Math.PI*2);ctx.clip();ctx.drawImage(logoSmall,32,15,52,52);ctx.restore();}}catch(e){}const fotoEl=document.getElementById('fotoIDC');let fotoUrl=fotoEl.style.backgroundImage.slice(5,-2).replace(/"/g,'');if(fotoUrl){try{let img=await loadImg(fotoUrl);if(img){const fx=40,fy=110,fw=200,fh=280,r=18;ctx.save();ctx.beginPath();ctx.roundRect(fx,fy,fw,fh,r);ctx.clip();const ir=img.width/img.height,br=fw/fh;let sx,sy,sw,sh;if(ir>br){sh=img.height;sw=sh*br;sx=(img.width-sw)/2;sy=0;}else{sw=img.width;sh=sw/br;sx=0;sy=0;}ctx.drawImage(img,sx,sy,sw,sh,fx,fy,fw,fh);ctx.restore();}}catch(e){}}ctx.fillStyle='rgba(255,255,255,0.4)';ctx.font='700 10px "Plus Jakarta Sans"';ctx.fillText('NAMA LENGKAP',280,165);ctx.fillStyle='#fff';let nama="{{ $namaFull }}";let fs=26;ctx.font=`800 ${fs}px "Plus Jakarta Sans"`;while(ctx.measureText(nama.toUpperCase()).width>520&&fs>16){fs--;ctx.font=`800 ${fs}px "Plus Jakarta Sans"`;}ctx.fillText(nama.toUpperCase(),280,200);ctx.fillStyle='rgba(255,255,255,0.4)';ctx.font='700 10px "Plus Jakarta Sans"';ctx.fillText('ANGKATAN',280,245);ctx.fillStyle='#fff';ctx.font='700 18px "Plus Jakarta Sans"';ctx.fillText('{{ $angkatan }}',280,270);ctx.fillStyle='rgba(255,255,255,0.3)';ctx.fillRect(280,385,48,1);ctx.font='400 9px "Plus Jakarta Sans"';ctx.fillText('PASKIBRA NESTA',340,388);ctx.fillStyle='#fff';ctx.font='800 20px "Plus Jakarta Sans"';ctx.fillText('PASKIBRA',96,42);ctx.fillStyle='rgba(255,255,255,0.7)';ctx.font='700 11px "Plus Jakarta Sans"';ctx.fillText('SMKN 1 TANGERANG',96,58);ctx.fillStyle='rgba(255,255,255,0.4)';ctx.font='700 9px "Plus Jakarta Sans"';ctx.fillText('LEVEL',780,32);ctx.fillStyle='#fff';ctx.font='700 15px "Plus Jakarta Sans"';ctx.textAlign='right';ctx.fillText('PPS {{ $angkatan }}',824,50);ctx.textAlign='left';const qrCanvas=document.querySelector('#qrcode canvas');if(qrCanvas){const qx=700,qy=380,qs=96;ctx.fillStyle='#fff';ctx.beginPath();ctx.roundRect(qx-8,qy-8,qs+16,qs+16,14);ctx.fill();ctx.drawImage(qrCanvas,qx,qy,qs,qs);}ctx.restore();const a=document.createElement('a');a.download=`idc_pps_{{ Str::slug($namaFull, '_') }}.jpg`;a.href=canvas.toDataURL('image/jpeg',1.0);a.click();}

// LOGIC BARU: KONFIRMASI DULU
let pendingFile = null;
let pendingDataUrl = null;
const dropArea=document.getElementById('dropArea');
const inputFoto=document.getElementById('inputFoto');
const fotoIDC=document.getElementById('fotoIDC');
const fotoEmpty=document.getElementById('fotoEmpty');
const modal=document.getElementById('confirmModal');
const previewModal=document.getElementById('previewModal');
const toast=document.getElementById('toast');

function showToast(type, msg){
  const icon = document.getElementById('toastIcon');
  const text = document.getElementById('toastText');
  text.textContent = msg;
  if(type==='success'){
    icon.innerHTML=`<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>`;
    icon.className="w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center text-white";
  } else if(type==='error'){
    icon.innerHTML=`<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>`;
    icon.className="w-7 h-7 rounded-full bg-red-500 flex items-center justify-center text-white";
  } else {
    icon.innerHTML=`…`;
    icon.className="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center";
  }
  toast.classList.remove('hidden');
  setTimeout(()=>toast.classList.add('hidden'),3000);
}

dropArea.addEventListener('click',()=>inputFoto.click());
inputFoto.addEventListener('change',e=>{const f=e.target.files[0];if(f) openConfirm(f);});
dropArea.addEventListener('dragover',e=>{e.preventDefault();dropArea.classList.add('border-[#9a0000]');});
dropArea.addEventListener('dragleave',e=>{e.preventDefault();dropArea.classList.remove('border-[#9a0000]');});
dropArea.addEventListener('drop',e=>{e.preventDefault();dropArea.classList.remove('border-[#9a0000]');const f=e.dataTransfer.files[0];if(f) openConfirm(f);});

function openConfirm(file){
  pendingFile=file;
  const reader=new FileReader();
  reader.onload=ev=>{
    pendingDataUrl=ev.target.result;
    previewModal.style.backgroundImage=`url('${pendingDataUrl}')`;
    modal.classList.remove('hidden');
  };
  reader.readAsDataURL(file);
}

document.getElementById('btnCancel').addEventListener('click',()=>{
  modal.classList.add('hidden');
  pendingFile=null; pendingDataUrl=null;
  inputFoto.value='';
});

document.getElementById('btnConfirm').addEventListener('click', async ()=>{
  if(!pendingFile) return;
  modal.classList.add('hidden');
  fotoIDC.style.backgroundImage=`url('${pendingDataUrl}')`;
  fotoIDC.classList.remove('hidden');
  fotoEmpty.classList.add('hidden');

  showToast('loading','Mengupload...');
  let finalFile=pendingFile;
  if(pendingFile.size>1024*1024){
    try{
      const bmp=await createImageBitmap(pendingFile);
      const c=document.createElement('canvas');c.width=800;c.height=bmp.height*(800/bmp.width);
      c.getContext('2d').drawImage(bmp,0,0,c.width,c.height);
      const blob=await new Promise(r=>c.toBlob(r,'image/jpeg',0.8));
      finalFile=new File([blob],pendingFile.name.replace(/\.\w+$/,'.jpg'),{type:'image/jpeg'});
    }catch(e){}
  }
  const fd=new FormData();fd.append('foto',finalFile);fd.append('_token','{{ csrf_token() }}');
  try{
    const res=await fetch("{{ route('pps.idc.foto') }}",{method:'POST',body:fd});
    const d=await res.json();
    if(d.success){
      showToast('success','Foto berhasil disimpan!');
    } else throw 0;
  }catch{
    showToast('error','Gagal upload, max 2MB');
  }
  pendingFile=null; pendingDataUrl=null; inputFoto.value='';
});
</script>
</body>
</html>