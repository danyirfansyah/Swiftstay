<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorit;
use App\Models\Ulasan;

class HomeController extends Controller
{
    public function tampilan() {
        return view('signin');
    }
    public function index(Request $request)
    {
        // Get the authenticated user
        $getUser = Auth::user();
        $user = Profile::where('id', $getUser->id)->first()->nama;
        $role = $getUser->role ?? 'Guest';

        // Fetch kost listings, with optional search filter
        $query = Kos::query();
        if ($request->filled('keyword')) {
            $query->where('Nama', 'like', '%' . $request->keyword . '%')
                  ->orWhere('Lokasi', 'like', '%' . $request->keyword . '%')
                  ->orWhere('Harga', 'like', '%' . $request->keyword . '%');
        }
        $kosts = $query->get();


        return view('homepage', compact('user', 'role', 'kosts'));
    }

    public function favorit() {
        $datarole = Auth::user();
        $user = Profile::where('id', $datarole->id)->first()->nama;
        $data = Favorit::all();
        return view("favorit",compact(['datarole','user','data']));
    }

    public function store(Request $request, $id)
{
    // Validasi data ulasan yang masuk
    $request->validate([
        'nilai' => 'required|integer|min:1|max:5',
        'ulasan' => 'required|string|max:500',
    ]);

    // Simpan data ulasan ke database (sesuaikan dengan model dan tabel ulasan Anda)
    Ulasan::create([
        'kost_id' => $request->kost_id,
        'user_id' => Auth::id(),
        'ulasan'  => $request->ulasan,
        'nilai'   => $request->nilai,
    ]);    
    // Redirect kembali ke halaman sebelumnya dengan pesan sukses
    return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan!');
}

}
