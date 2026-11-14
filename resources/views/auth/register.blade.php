<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Lengkapi Pendaftaran - OSI PASKIBRA</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans', sans-serif}
.bg-paskibra{background: radial-gradient(130% 130% at 0% 0%, #e30613 0%, #9a0000 40%, #0a0a0a 100%);}
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
<div class="hidden lg:block"><h1 class="text-[60px] font-[800] leading-[0.9]">PASKIBRA<br>NESTA</h1><p class="mt-10 text-[22px] font-[300]">Disiplin Menempa Jiwa,<br><span class="font-[600]">Kebersamaan Menguatkan Langkah</span></p></div>
</div>
<div class="w-full max-w-[500px] shrink-0 relative z-10"><div class="glass-card rounded-[24px] md:rounded-[28px] p-5 md:p-10 shadow-[0_30px_90px_rgba(0,0,0,0.6)] border border-white/20">
<div class="text-center md:text-left mb-5 md:mb-6"><h3 class="text-[20px] md:text-[24px] font-[800] text-[#c40000] tracking-tight">Lengkapi Pendaftaran</h3><p class="text-[12px] text-zinc-500 mt-1.5 break-words">Email: <b class="text-zinc-800 break-all">{{ $email }}</b> <span class="text-green-600">(terverifikasi)</span></p><div class="w-10 h-[3px] bg-[#c40000] mt-3 rounded-full mx-auto md:mx-0"></div></div>

@if($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-[13px] text-center">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif

<form method="POST" action="{{ url('/register/'.$token) }}" class="space-y-3">
@csrf
<div><label class="text-[11px] font-bold tracking-widest text-zinc-500 ml-1">Nama Lengkap</label><input type="text" name="name" required value="{{ old('name') }}" placeholder="Nama Lengkap" class="w-full mt-1.5 px-4 py-3.5 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium transition"></div>

<div><label class="text-[11px] font-bold tracking-widest text-zinc-500 ml-1">Username</label><input type="text" name="username" required minlength="4" value="{{ old('username') }}" placeholder="Username minimal 4 huruf" class="w-full mt-1.5 px-4 py-3.5 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium transition"></div>

<div><label class="text-[11px] font-bold tracking-widest text-zinc-500 ml-1">Angkatan</label><input type="number" name="angkatan" required min="2015" max="2030" value="{{ old('angkatan') }}" placeholder="Contoh: 2023" inputmode="numeric" class="w-full mt-1.5 px-4 py-3.5 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium transition"><p class="text-[10px] text-zinc-400 mt-2 ml-1">Isi tahun masuk, nanti admin atur role manual</p></div>

<div><label class="text-[11px] font-bold tracking-widest text-zinc-500 ml-1">Password</label><input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full mt-1.5 px-4 py-3.5 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium transition"></div>

<div><label class="text-[11px] font-bold tracking-widest text-zinc-500 ml-1">Ulangi Password</label><input type="password" name="password_confirmation" required placeholder="Ulangi Password" class="w-full mt-1.5 px-4 py-3.5 rounded-full bg-[#f0f7ff] border border-zinc-200 focus:border-[#c40000] focus:ring-4 focus:ring-red-100 outline-none text-[14px] font-medium transition"></div>

<div class="pt-2 md:pt-3"><button type="submit" class="w-full login-btn text-white font-bold py-3.5 md:py-4 rounded-full text-[15px] shadow-[0_4px_20px_rgba(227,6,19,0.4)] tracking-wide active:scale-[0.98] transition">BUAT AKUN</button></div>
</form>
<div class="text-center mt-5 pt-4 border-t border-zinc-100"><p class="text-[10px] font-[600] tracking-[0.3em] text-zinc-400">PASKIBRA NESTA</p><p class="text-[9px] text-zinc-300 mt-1">© 2026 ORGANIZATIONAL SYSTEM</p></div>
</div></div>
</div></div>
</body>
</html>