@extends('layout.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
    {{-- Header dengan Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-slate-200">Daftar Kategori</h2>
        <button type="button"
                onclick="document.getElementById('tambahKategoriModal').classList.remove('hidden')"
                class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300 shadow-md">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori
        </button>
    </div>

    {{-- Tabel Daftar Kategori --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-700 text-slate-300 uppercase">
                <tr>
                    <th class="py-3 px-4 text-left w-16">No</th>
                    <th class="py-3 px-4 text-left">Nama Kategori</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-slate-400">
                @forelse($kategoris as $index => $kategori)
                    <tr class="border-b border-slate-700 hover:bg-slate-700/50">
                        <td class="px-4 py-3 align-middle">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 align-middle font-semibold text-slate-200">{{ $kategori->nama_kategori }}</td>
                        <td class="px-4 py-3 align-middle">
                            <div class="flex items-center justify-center space-x-3">
                                <button onclick="openEditModal({{ $kategori->id_kategori }}, '{{ addslashes($kategori->nama_kategori) }}')" class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus kategori ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-slate-500">
                            Tidak ada data kategori ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div id="tambahKategoriModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex justify-center items-center hidden">
    <div class="bg-slate-800 rounded-xl shadow-lg w-full max-w-md border border-slate-700">
        <div class="flex justify-between items-center border-b border-slate-700 p-4">
            <h3 class="text-lg font-medium text-slate-200">Tambah Kategori Baru</h3>
            <button type="button" onclick="document.getElementById('tambahKategoriModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="p-6">
                <label for="nama_kategori_add" class="block text-slate-300 text-sm font-medium mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama_kategori_add" class="bg-slate-900 border-slate-600 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2" required>
            </div>
            <div class="bg-slate-700/50 px-6 py-4 flex justify-end space-x-3 rounded-b-xl">
                <button type="button" onclick="document.getElementById('tambahKategoriModal').classList.add('hidden')" class="bg-slate-600 hover:bg-slate-500 text-slate-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                    Batal
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editKategoriModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex justify-center items-center hidden">
    <div class="bg-slate-800 rounded-xl shadow-lg w-full max-w-md border border-slate-700">
        <div class="flex justify-between items-center border-b border-slate-700 p-4">
            <h3 class="text-lg font-medium text-slate-200">Edit Kategori</h3>
            <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editKategoriForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6">
                <label for="edit_nama_kategori" class="block text-slate-300 text-sm font-medium mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="edit_nama_kategori" class="bg-slate-900 border-slate-600 text-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2" required>
            </div>
            <div class="bg-slate-700/50 px-6 py-4 flex justify-end space-x-3 rounded-b-xl">
                <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="bg-slate-600 hover:bg-slate-500 text-slate-200 font-medium py-2 px-4 rounded-lg transition duration-300">
                    Batal
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, nama) {
        // Setel action form edit sesuai dengan ID kategori
        const form = document.getElementById('editKategoriForm');
        form.action = `{{ url('kategori') }}/${id}`;

        // Isi nilai input dengan nama kategori saat ini
        const inputNama = document.getElementById('edit_nama_kategori');
        inputNama.value = nama;

        // Tampilkan modal edit
        const modal = document.getElementById('editKategoriModal');
        modal.classList.remove('hidden');
    }
</script>
@endpush
