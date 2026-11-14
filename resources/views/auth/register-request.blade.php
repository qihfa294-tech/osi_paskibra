<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>OSI - PASKIBRA NESTA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
html, body{ background:#0a0a0a; }
body{font-family:'Plus Jakarta Sans', sans-serif}
.bg-paskibra{
background: radial-gradient(130% 130% at 0% 0%, #e30613 0%, #9a0000 40%, #0a0a0a 100%);
background-attachment: fixed;
}
.glass-card{background: rgba(255,255,255,0.97);backdrop-filter: blur(24px);}
.wm-bg{
position:absolute;
left:50%;
top:42%;
transform:translate(-50%,-50%) rotate(-18deg);
width:130%;
max-width:none;
opacity:0.08;
pointer-events:none;
z-index:0;
}
.wm-bg-desktop{
position:absolute;
left:-8%;
bottom:-20%;
width:85%;
opacity:0.04;
pointer-events:none;
transform:rotate(-18deg);
z-index:0;
}
.login-btn{background: linear-gradient(180deg, #e30613 0%, #8a0000 100%);}
@keyframes slideIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
@keyframes shrink{from{width:100%}to{width:0%}}
.toast-in{animation: slideIn 0.4s ease-out}
.progress-bar{animation: shrink 6s linear forwards}
@media (max-width: 1023px){
  body{ overflow-y:auto !important; }
  .glass-card{
    background: #ffffff !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.25) !important;
    border-radius: 24px !important;
  }
}
</style>
</head>
<body class="bg-paskibra min-h-[100dvh] flex flex-col p-4 md:p-8 relative overflow-x-hidden overflow-y-auto">
{{-- WATERMARK HP: GEDE DITENGAH, BIAR GAK SEPI --}}
<img src="{{ asset('logo.jpeg') }}" class="wm-bg lg:hidden" alt="">
{{-- WATERMARK DESKTOP: TETEP KAYAK DULU --}}
<img src="{{ asset('logo.jpeg') }}" class="wm-bg-desktop hidden lg:block" alt="Logo">
<div class="w-full max-w-[1280px] mx-auto flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-6 lg:gap-20 z-10 flex-1 lg:min-h-[calc(100dvh-4rem)] py-2 lg:py-0">
<div class="flex-1 text-white flex flex-col justify-center lg:pl-16 text-center lg:text-left w-full shrink-0">
<!-- HEADER HP - UDAH GAK NUMPUK LAGI -->
<div class="flex lg:hidden w-full flex-col items-center justify-center pt-6 pb-2">
  <div class="flex items-center gap-3">
    <img src="{{ asset('logo_osi.png') }}" alt="OSI Logo" class="w-[52px] h-[52px] object-contain drop-shadow-lg">
    <div class="text-left leading-none">
      <div class="text-[42px] font-[800] text-white tracking-tighter italic leading-none">OSI</div>
    </div>
  </div>
  <p class="text-[13px] font-[700] tracking-[0.18em] text-white/90 mt-3 uppercase">Mobile • by Paskibra Nesta</p>
  <div class="w-8 h-[2px] bg-white/30 rounded-full my-3"></div>
  <p class="text-[13px] text-white/80 leading-relaxed font-[400]">Disiplin Menempa Jiwa,<br><span class="font-[700] text-white">Kebersamaan Menguatkan Langkah</span></p>
</div>
<div class="hidden lg:flex items-center gap-6 mb-10"><img src="{{ asset('logo_osi.png') }}" alt="OSI Logo" class="w-[110px] h-[110px] object-contain"><div class="flex flex-col"><div class="text-[68px] font-[800] tracking-tighter leading-[0.85]">OSI</div><div class="text-[12px] font-bold tracking-[0.28em] text-white/70 mt-2.5">BY PASKIBRA NESTA</div></div></div>
<div class="hidden lg:block mx-auto lg:mx-0"><h1 class="text-[60px] font-[800] leading-[0.9] tracking-tight">PASKIBRA<br>NESTA</h1><h2 class="text-[14px] font-[600] mt-4 text-white/60 tracking-[0.22em] uppercase">Organizational System Integration</h2><p class="mt-10 text-[22px] font-[300] leading-snug text-white/90">Disiplin Menempa Jiwa,<br><span class="font-[600] text-white">Kebersamaan Menguatkan Langkah</span></p></div>
</div>
<div class="w-full max-w-[500px] shrink-0 relative z-10"><div class="glass-card rounded-[24px] md:rounded-[28px] p-5 md:p-10 shadow-[0_30px_90px_rgba(0,0,0,0.6)] border border-white/20">
<div class="text-center mb-5 md:mb-6"><h3 class="text-[20px] md:text-[24px] font-[800] text-[#c40000] tracking-tight">Buat Akun Baru</h3><p class="text-[12px] text-zinc-400 mt-2">Masukkan Gmail untuk dapat link pendaftaran</p><div class="w-10 h-[3px] bg-[#c40000] mx-auto mt-2.5 rounded-full"></div></div>

{{-- NOTIF CAKEP + AUTO HILANG 6 DETIK --}}
@if(session('success'))
<div id="toast-success" class="toast-in mb-5 relative overflow-hidden bg-white border border-green-200 rounded-2xl shadow-[0_8px_24px_rgba(34,197,94,0.15)]">
<div class="flex items-start gap-3 px-4 py-3.5">
<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
</div>
<div class="flex-1 min-w-0">
<p class="text-[13px] font-bold text-zinc-800 leading-tight">Email Terkirim!</p>
<p class="text-[11.5px] text-zinc-500 mt-1 leading-snug break-words">Cek inbox <span class="font-semibold text-zinc-700">{{ Str::limit(session('success'), 40) }}</span></p>
</div>
<button onclick="closeToast()" class="text-zinc-400 hover:text-zinc-600 -mt-1 shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
</div>
<div class="h-[3px] bg-green-100 w-full"><div id="progress" class="h-full bg-green-500 progress-bar"></div></div>
</div>
@endif

@if($errors->any())
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13px] text-center">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register.request.link') }}" class="space-y-3 md:space-y-4">
@csrf
<div class="relative">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
<input type="email" name="email" required placeholder="contoh: emailmu@gmail.com" class="w-full pl-12 pr-4 py-3.5 md:py-4 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium">
</div>
<button type="submit" class="w-full login-btn text-white font-bold py-3.5 md:py-4 rounded-full text-[15px] shadow-[0_4px_20px_rgba(227,6,19,0.4)] active:scale-[0.98] transition">Kirim Link ke Email</button>
</form>

<div class="mt-5 md:mt-6 pt-5 border-t border-zinc-100 text-center">
  <p class="text-[12px] text-zinc-400">Sudah punya akun?</p>
  <a href="{{ url('/login') }}" class="mt-3 block w-full bg-zinc-900 text-white font-bold py-3.5 rounded-full text-[14px] text-center active:scale-[0.98] transition">Login</a>
</div>

{{-- FOOTER BALIK LAGI - INI YANG KEMARIN KEHAPUS --}}
<div class="text-center mt-5 pt-4 border-t border-zinc-100"><p class="text-[10px] font-[600] tracking-[0.3em] text-zinc-400">PASKIBRA NESTA</p><p class="text-[9px] text-zinc-300 mt-1">© 2026 ORGANIZATIONAL SYSTEM</p></div>
</div></div>
</div></div>

<script>
function closeToast(){
  const t = document.getElementById('toast-success');
  if(t){ t.style.transition='all 0.3s'; t.style.opacity='0'; t.style.transform='translateY(-10px)'; setTimeout(()=>t.remove(),300); }
}
setTimeout(()=>{ closeToast(); }, 6000);
</script>
</body>
</html>