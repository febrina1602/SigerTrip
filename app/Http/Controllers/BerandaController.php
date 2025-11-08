<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    // Beranda untuk wisatawan
    public function wisatawan()
    {
        $categories = DestinationCategory::all();
        
        return view('beranda.wisatawan', compact('categories'));
    }
}
