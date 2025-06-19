<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index()
    {
        $barang = Barang::with('kategori')->get();
        $kategori = Kategori::all();
        return view('barang.index', compact('barang', 'kategori'));
    }

    // Menampilkan form tambah barang
    public function create()
    {
        $kategori = Kategori::all();
        return view('barang.create', compact('kategori'));
    }

    // Menyimpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang|max:255',
            'nama_barang' => 'required|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'jumlah' => 'required|integer|min:0',
            'tersedia' => 'required|integer|min:0',
            'dipinjam' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required',
            'status' => 'required|in:tersedia,tidak tersedia',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/barang');
            $data['foto'] = basename($path);
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan detail barang
    public function show($id_barang)
    {
        $barang = Barang::with('kategori')->findOrFail($id_barang);
        return view('barang.show', compact('barang'));
    }

    // Menampilkan form edit barang
    public function edit($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    // Mengupdate barang
    public function update(Request $request, $id_barang)
    {
        $barang = Barang::findOrFail($id_barang);

        $request->validate([
            'kode_barang' => [
                'required',
                'max:255',
                Rule::unique('barang')->ignore($barang->id_barang, 'id_barang')
            ],
            'nama_barang' => 'required|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'jumlah' => ['required', 'integer', 'min:'.$barang->dipinjam],
            'tersedia' => 'required|integer|min:0',
            'dipinjam' => 'required|integer|min:0',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required',
            'status' => 'required|in:tersedia,tidak tersedia',
            'keterangan' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($barang->foto) {
                Storage::delete('public/barang/' . $barang->foto);
            }
            
            $path = $request->file('foto')->store('public/barang');
            $data['foto'] = basename($path);
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    // Menghapus barang
    public function destroy($id_barang) 
    {
        $barang = Barang::findOrFail($id_barang);
        if ($barang->foto) {
            Storage::delete('public/barang/'.$barang->foto);
        }
        $barang->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }
    // API untuk mobile: daftar barang
    public function apiList()
    {
        $barang = Barang::with('kategori')->get();
        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    // List Barang untuk Mobile
    public function mobileList()
    {
        $barang = Barang::with('kategori')
            ->where('status', 'tersedia')
            ->get()
            ->map(function ($item) {
                return [
                    'id_barang' => $item->id_barang,
                    'kode_barang' => $item->kode_barang,
                    'nama_barang' => $item->nama_barang,
                    'kategori' => $item->kategori->nama_kategori,
                    'jumlah' => $item->jumlah,
                    'tersedia' => $item->tersedia,
                    'kondisi' => $item->kondisi,
                    'lokasi' => $item->lokasi,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }
}
