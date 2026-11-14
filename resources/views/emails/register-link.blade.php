<!DOCTYPE html>
<html>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family: 'Plus Jakarta Sans', Helvetica, Arial, sans-serif;">
<div style="max-width:480px; margin:40px auto; background:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.12);">

{{-- HEADER - SAMA PERSIS KAYAK LOGIN.BLADE --}}
<div style="background: radial-gradient(130% 130% at 0% 0%, #e30613 0%, #9a0000 40%, #0a0a0a 100%); padding:32px 30px; text-align:center;">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td style="padding-right:14px; vertical-align:middle;">
        <img src="{{ $message->embed(public_path('logo_osi.png')) }}" alt="OSI" width="48" height="48" style="display:block; width:48px; height:48px; object-fit:contain;">
      </td>
      <td style="text-align:left; vertical-align:middle;">
        <div style="color:#ffffff; font-size:36px; font-weight:800; letter-spacing:-2px; line-height:0.85; font-style:italic;">OSI</div>
        <div style="color:rgba(255,255,255,0.7); font-size:10px; font-weight:700; letter-spacing:3.5px; margin-top:5px;">BY PASKIBRA NESTA</div>
      </td>
    </tr>
  </table>
</div>

<div style="padding:32px 28px 28px 28px; text-align:center;">
  
  <h2 style="margin:0; font-size:20px; font-weight:800; color:#18181b; letter-spacing:-0.4px;">Link Pendaftaran Akun</h2>
  <div style="width:32px; height:3px; background:#c40000; margin:10px auto 0 auto; border-radius:99px;"></div>
  
  <p style="margin:16px 0 0 0; font-size:13px; color:#71717a; line-height:1.7;">
    Halo Calon Keluarga PASKIBRA NESTA...!<br>
    Kami menerima permintaan pendaftaran akun kamu.
  </p>

  {{-- BUTTON TANPA PANAH, SAMA KAYAK LOGIN.BLADE --}}
  <div style="text-align:center; margin:26px 0 0 0;">
    <a href="{{ $link }}" style="display:inline-block; background: linear-gradient(180deg, #e30613 0%, #8a0000 100%); color:#ffffff; text-decoration:none; font-weight:800; padding:14px 36px; border-radius:999px; font-size:13.5px; letter-spacing:0.3px; box-shadow:0 8px 20px rgba(227,6,19,0.35);">
      Lanjut Buat Akun
    </a>
  </div>

  <div style="background:#f0f7ff; border:1px solid #e4e8f5; border-radius:12px; padding:14px 16px; margin-top:26px;">
    <p style="margin:0; font-size:11px; color:#8a8a9a; text-align:center; line-height:1.4;">Jika tombol tidak bisa diklik, salin link ini:</p>
    <p style="margin:8px 0 0 0; font-size:11px; color:#9a0000; word-break:break-all; text-align:center; line-height:1.6;">
      {{ $link }}
    </p>
  </div>

  <p style="font-size:11px; color:#a1a1aa; text-align:center; margin-top:22px; line-height:1.6;">
    Link ini hanya berlaku <b style="color:#52525b;">24 Jam</b> dan hanya bisa dipakai sekali.<br>
    Jangan bagikan link ini ke orang lain.
  </p>
</div>

<div style="background:#fafafa; border-top:1px solid #f0f0f0; padding:16px; text-align:center;">
  <p style="margin:0; font-size:10px; font-weight:700; letter-spacing:3px; color:#a1a1aa;">PASKIBRA NESTA</p>
  <p style="margin:4px 0 0 0; font-size:9px; color:#d4d4d8;">© 2026 ORGANIZATIONAL SYSTEM INTEGRATION</p>
</div>

</div>
</body>
</html>