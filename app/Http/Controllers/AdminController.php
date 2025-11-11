<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\User;
use App\Models\DestinationCategory;

class AdminController extends Controller
{
   public function beranda()
    {
        $totalWisata = Destination::count();
        $totalKategori = DestinationCategory::count();
        $totalUser = User::count();
        $destinations = Destination::latest()->take(6)->get();

        return view('admin.beranda', compact(
            'totalWisata', 'totalKategori', 'totalUser', 'destinations'
        ));
    }

    public function pemandu()
    {
        return view('admin.pemandu');
    }

    public function akun()
    {
        return view('admin.akun');
    }

    public function pasar()
    {
        return view('admin.pasar');
    }

    public function verifikasi($id)
    {
        // contoh implementasi verifikasi akun
    }
}
