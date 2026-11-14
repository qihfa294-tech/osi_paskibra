@extends('layouts.app')
@section('content')
<div class="p-6 bg-[#fff5f5] min-h-screen">
  <h1 class="text-2xl font-bold">Validasi E-Kas - Cash & QRIS + Bukti Foto</h1>
  
  @foreach($kasPending as $kas)
  <div class="bg-white p-4 rounded-xl mt-4 flex gap-4">
    <img src="{{ asset('storage/'.$kas->bukti_foto) }}" class="w-32 h-32 object-cover rounded-lg border">
    <div class="flex-1">
      <p><b>{{$kas->user->name}}</b> - Rp {{ number_format($kas->amount) }} - {{$kas->metode}}</p>
      <p class="text-xs text-gray-500">Angkatan: {{$kas->angkatan}} | {{ $kas->created_at->format('d M Y H:i') }}</p>
      <div class="mt-3 flex gap-2">
        <form method="POST" action="{{ route('dev.accKas', $kas->id) }}">
          @csrf
          <input type="hidden" name="status" value="valid">
          <button class="bg-green-600 text-white px-4 py-1 rounded-full text-sm">ACC Valid</button>
        </form>
        <form method="POST" action="{{ route('dev.accKas', $kas->id) }}">
          @csrf
          <input type="hidden" name="status" value="tolak">
          <button class="bg-red-600 text-white px-4 py-1 rounded-full text-sm">Tolak</button>
        </form>
      </div>
    </div>
  </div>
  @endforeach

  @if($kasPending->isEmpty())
    <p class="mt-10 text-center text-gray-400">Gak ada pending, semua udah divalidasi PPL / Developer</p>
  @endif
</div>
@endsection