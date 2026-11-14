<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>OSI - PASKIBRA NESTA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans', sans-serif}
.bg-paskibra{
background: radial-gradient(130% 130% at 0% 0%, #e30613 0%, #9a0000 40%, #0a0a0a 100%);
}
.glass-card{
background: rgba(255,255,255,0.97);
backdrop-filter: blur(24px);
}
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
.login-btn{
background: linear-gradient(180deg, #e30613 0%, #8a0000 100%);
}
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

<!-- LEFT -->
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

<div class="hidden lg:flex items-center gap-6 mb-10">
<img src="{{ asset('logo_osi.png') }}" alt="OSI Logo" class="w-[110px] h-[110px] object-contain">
<div class="flex flex-col">
<div class="text-[68px] font-[800] tracking-tighter leading-[0.85]">OSI</div>
<div class="text-[12px] font-bold tracking-[0.28em] text-white/70 mt-2.5">BY PASKIBRA NESTA</div>
</div>
</div>

<div class="hidden lg:block mx-auto lg:mx-0">
<h1 class="text-[60px] font-[800] leading-[0.9] tracking-tight">PASKIBRA<br>NESTA</h1>
<h2 class="text-[14px] font-[600] mt-4 text-white/60 tracking-[0.22em] uppercase">Organizational System Integration</h2>
<p class="mt-10 text-[22px] font-[300] leading-snug text-white/90">
Disiplin Menempa Jiwa,<br>
<span class="font-[600] text-white">Kebersamaan Menguatkan Langkah</span>
</p>
</div>
</div>

<!-- RIGHT CARD -->
<div class="w-full max-w-[500px] shrink-0 relative z-10">
<div class="glass-card rounded-[24px] md:rounded-[28px] p-5 md:p-10 shadow-[0_30px_90px_rgba(0,0,0,0.6)] border border-white/20">
<div class="text-center mb-5 md:mb-8">
<h3 class="text-[20px] md:text-[24px] font-[700] md:font-[800] text-[#c40000] tracking-tight">Silahkan Login</h3>
<div class="w-10 h-[3px] bg-[#c40000] mx-auto mt-2.5 rounded-full"></div>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-[13px] text-center">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13px] text-center">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ url('/login') }}" class="space-y-3 md:space-y-4">
@csrf
<div class="relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></span>
<input type="text" name="login" value="{{ old('login', old('username')) }}" required placeholder="Username atau Email" class="w-full pl-12 pr-4 py-3.5 md:py-4 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium">
</div>
<div class="relative group">
<span class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2.5"/><path d="M7 11V8a5 5 0 0 1 10 0v3"/></svg></span>
<input type="password" name="password" id="password" required placeholder="Enter your password" class="w-full pl-12 pr-12 py-3.5 md:py-4 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium">
<button type="button" onclick="document.getElementById('password').type = document.getElementById('password').type==='password'?'text':'password'" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
</div>
<div class="pt-2 md:pt-4 space-y-3">
<button type="submit" class="w-full login-btn text-white font-bold py-3.5 md:py-4 rounded-full text-[15px] shadow-[0_4px_20px_rgba(227,6,19,0.4)] active:scale-[0.98] transition">Login</button>

<div class="flex items-center gap-3 py-1">
<div class="flex-1 h-[1px] bg-zinc-200"></div>
<span class="text-[11px] font-bold text-zinc-400 tracking-widest">OR</span>
<div class="flex-1 h-[1px] bg-zinc-200"></div>
</div>

<a href="{{ url('/register') }}" class="block w-full bg-black md:bg-[#e30613] text-white font-bold py-3.5 md:py-4 rounded-full text-center text-[14px] hover:opacity-90 active:scale-[0.98] transition">Create Account</a>

</div>
</form>

<div class="text-center mt-5 md:mt-6 pt-4 md:pt-5 border-t border-zinc-100">
<p class="text-[10px] font-[600] tracking-[0.3em] text-zinc-400">PASKIBRA NESTA</p>
<p class="text-[9px] text-zinc-300 mt-1">© 2026 ORGANIZATIONAL SYSTEM</p>
</div>
</div>
</div>

</div>
</div>
</body>
</html>