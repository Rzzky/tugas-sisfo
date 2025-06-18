@extends('layout.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 md:p-8 border border-slate-700">
    <div class="flex items-center mb-6">
        <a href="{{ route('barang.index') }}" class="text-slate-400 hover:text-indigo-400 mr-4" title="Kembali">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <h2 class="text-2xl font-semibold text-slate-200">Formulir Tambah Barang Baru</h2>
    </div>

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

    <form action="{{ route('barang.store') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="dipinjam" value="0">
        <input type="hidden" name="tersedia" id="hidden_tersedia" value="{{ old('jumlah', 1) }}">
        <input type="hidden" name="status" id="hidden_status" value="tersedia">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-6">
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-slate-300">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: BRG-001" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-slate-300">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Laptop Dell XPS 15" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>

                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-slate-300">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-slate-300">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}" min="0" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>

                <div>
                    <label for="kondisi" class="block text-sm font-medium text-slate-300">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="kondisi" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                 <div>
                    <label for="lokasi" class="block text-sm font-medium text-slate-300">Lokasi Penyimpanan <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Lemari A, Rak 2" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200" required>
                </div>
            </div>
        </div>

        {{-- Keterangan --}}
        <div class="border-t border-slate-700 pt-6">
            <label for="keterangan" class="block text-sm font-medium text-slate-300">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" rows="4" placeholder="Spesifikasi, catatan, atau informasi tambahan lainnya" class="mt-1 block w-full bg-slate-900 border-slate-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-200">{{ old('keterangan') }}</textarea>
        </div>


        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-slate-700">
            <a href="{{ route('barang.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md">
                <i class="fas fa-save mr-2"></i>Simpan Barang
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

    function updateStok() {
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        hiddenTersediaInput.value = jumlah;
        hiddenStatusInput.value = jumlah > 0 ? 'tersedia' : 'tidak tersedia';
    }

    jumlahInput.addEventListener('input', updateStok);
});
</script>
@endpush
