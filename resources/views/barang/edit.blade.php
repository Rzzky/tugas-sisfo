@extends('layout.app')

@section('title', 'Edit Barang')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('barang.index') }}" class="text-indigo-600 hover:text-indigo-800 mr-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-semibold text-gray-800">Edit Barang</h2>
        </div>

        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="id_kategori" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $k->id_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="kondisi" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="tersedia" class="block text-sm font-medium text-gray-700 mb-1">Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="tersedia" id="tersedia" value="{{ old('tersedia', $barang->tersedia) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="dipinjam" class="block text-sm font-medium text-gray-700 mb-1">Dipinjam <span class="text-red-500">*</span></label>
                    <input type="number" name="dipinjam" id="dipinjam" value="{{ old('dipinjam', $barang->dipinjam) }}" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Pilih Status</option>
                        <option value="tersedia" {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ old('status', $barang->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('barang.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-300">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memeriksa jumlah
        const jumlahInput = document.getElementById('jumlah');
        const tersediaInput = document.getElementById('tersedia');
        const dipinjamInput = document.getElementById('dipinjam');

        function validasiJumlah() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const tersedia = parseInt(tersediaInput.value) || 0;
            const dipinjam = parseInt(dipinjamInput.value) || 0;

            if (tersedia + dipinjam !== jumlah) {
                alert('Jumlah barang harus sama dengan tersedia + dipinjam');
                return false;
            }
            return true;
        }

        // Event listeners untuk validasi jumlah saat nilai berubah
        jumlahInput.addEventListener('change', function() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const tersedia = parseInt(tersediaInput.value) || 0;
            const dipinjam = parseInt(dipinjamInput.value) || 0;

            if (jumlah < tersedia + dipinjam) {
                alert('Jumlah tidak boleh kurang dari tersedia + dipinjam');
                jumlahInput.value = tersedia + dipinjam;
            }
        });

        tersediaInput.addEventListener('change', function() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const tersedia = parseInt(tersediaInput.value) || 0;

            if (tersedia > jumlah) {
                alert('Jumlah tersedia tidak boleh lebih dari jumlah total');
                tersediaInput.value = jumlah;
                dipinjamInput.value = 0;
            } else {
                dipinjamInput.value = jumlah - tersedia;
            }
        });

        dipinjamInput.addEventListener('change', function() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const dipinjam = parseInt(dipinjamInput.value) || 0;

            if (dipinjam > jumlah) {
                alert('Jumlah dipinjam tidak boleh lebih dari jumlah total');
                dipinjamInput.value = 0;
                tersediaInput.value = jumlah;
            } else {
                tersediaInput.value = jumlah - dipinjam;
            }
        });

        // Validasi form saat submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validasiJumlah()) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
