@extends('layout.app')

@section('title', 'Edit Barang')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    <div class="flex items-center mb-6">
        <a href="{{ route('barang.index') }}" class="text-slate-400 hover:text-indigo-400 mr-4" title="Kembali">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h2 class="text-2xl font-semibold text-slate-200">Formulir Edit Barang</h2>
    </div>

    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="bg-red-900/50 border border-red-700 text-red-300 p-4 mb-4 rounded-lg">
            <p class="font-bold">Terjadi Kesalahan Validasi:</p>
            <ul class="list-disc ml-5 mt-2 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="dipinjam" value="{{ $barang->dipinjam }}">
        <input type="hidden" name="tersedia" id="hidden_tersedia" value="{{ old('tersedia', $barang->tersedia) }}">
        <input type="hidden" name="status" id="hidden_status" value="{{ old('status', $barang->status) }}">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            {{-- Kolom Kiri: Informasi Dasar --}}
            <div class="space-y-6">
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-slate-300">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-slate-300">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>

                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-slate-300">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label for="foto" class="block text-sm font-medium text-slate-300">Ganti Foto Barang (Opsional)</label>
                <div class="mt-1">
                    {{-- Tampilkan foto saat ini --}}
                    <img id="foto_preview" src="{{ $barang->foto ? asset('storage/barang/' . $barang->foto) : 'https://placehold.co/600x400/1e293b/94a3b8?text=Tidak+Ada+Foto' }}" alt="Foto Barang" class="w-full h-40 object-cover rounded-md mb-2">
                </div>
                <div class="flex text-sm text-slate-500">
                    <label for="foto" class="relative cursor-pointer bg-slate-700 rounded-md font-medium text-indigo-400 hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-slate-800 focus-within:ring-indigo-500 px-2 py-1">
                        <span>Pilih file baru...</span>
                        <input id="foto" name="foto" type="file" class="sr-only" accept="image/*">
                    </label>
                </div>
                 <p class="text-xs text-slate-600 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</p>
            </div>
            
            {{-- Kolom Kanan: Informasi Fisik --}}
            <div class="space-y-6">
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-slate-300">Jumlah Total <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" min="{{ $barang->dipinjam }}" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                    <p class="text-xs text-slate-500 mt-1">Jumlah total tidak boleh kurang dari yang sedang dipinjam ({{ $barang->dipinjam }}).</p>
                </div>

                <div>
                    <label for="kondisi" class="block text-sm font-medium text-slate-300">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="kondisi" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                        <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                 <div>
                    <label for="lokasi" class="block text-sm font-medium text-slate-300">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>
            </div>
        </div>

        {{-- Info Stok (Display Only) --}}
        <div class="border-t border-b border-slate-700 py-6">
            <p class="text-md font-medium text-slate-200 mb-4">Informasi Stok Saat Ini (Otomatis)</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <div>
                   <label class="block text-sm font-medium text-slate-400">Tersedia</label>
                   <p class="mt-1 text-lg font-semibold text-green-400" id="display_tersedia">{{ $barang->tersedia }}</p>
               </div>
               <div>
                   <label class="block text-sm font-medium text-slate-400">Dipinjam</label>
                   <p class="mt-1 text-lg font-semibold text-yellow-400">{{ $barang->dipinjam }}</p>
               </div>
            </div>
       </div>

        {{-- Keterangan --}}
        <div>
            <label for="keterangan" class="block text-sm font-medium text-slate-300">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="4" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200">{{ old('keterangan', $barang->keterangan) }}</textarea>
        </div>


        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-slate-700">
            <a href="{{ route('barang.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah');
    const hiddenTersediaInput = document.getElementById('hidden_tersedia');
    const hiddenStatusInput = document.getElementById('hidden_status');
    const displayTersedia = document.getElementById('display_tersedia');
    const dipinjam = parseInt("{{ $barang->dipinjam }}") || 0;
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('foto_preview');

    fotoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            fotoPreview.src = URL.createObjectURL(file);
        }
    });
    
    function updateStok() {
        const jumlah = parseInt(jumlahInput.value) || 0;
        const stokTersedia = jumlah - dipinjam;

        // Update a tampilan
        displayTersedia.textContent = stokTersedia;

        // Update nilai input tersembunyi yang akan dikirim ke server
        hiddenTersediaInput.value = stokTersedia;
        hiddenStatusInput.value = stokTersedia > 0 ? 'tersedia' : 'tidak tersedia';
    }

    jumlahInput.addEventListener('input', updateStok);
});
</script>
@endpush
