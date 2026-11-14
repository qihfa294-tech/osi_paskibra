<?php
namespace App\Http\Controllers\PPL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index()
    {
        return view('ppl.ekas.index');
    }

    public function tab($tipe)
    {
        // $tipe = capas|ppl|pps
        return view('ppl.ekas.tab', compact('tipe'));
    }

    public function settingStore(Request $request)
    {
        // buat set nominal/minggu + persen
        return back()->with('success','Setting kas disimpan');
    }

    public function store(Request $request)
    {
        // buat setor PPL (Tunai/QRIS)
        return back()->with('success','Setoran berhasil');
    }
}