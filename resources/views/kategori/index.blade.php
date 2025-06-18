@extends('layout.app')

@section('title', 'Kategori')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Kategori</h2>
            <button type="button"
                    onclick="document.getElementById('tambahKategoriModal').classList.remove('hidden')"
                    class="bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-2 px-4 rounded-lg transition duration-300 flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kategori
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $kategori)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $kategori->nama_kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $kategori->deksripsi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm">
                                <div class="flex space-x-2">
                                    <button onclick="editKategori({{ $kategori->id_kategori }}, '{{ $kategori->nama_kategori }}', '{{ $kategori->deksripsi }}')"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md transition duration-300">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md transition duration-300"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-center text-gray-500">Tidak ada data kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div id="tambahKategoriModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-medium text-gray-900">Tambah Kategori</h3>
                <button type="button" onclick="document.getElementById('tambahKategoriModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="p-4">
                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-gray-700 text-sm font-medium mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2 border" required>
                    </div>
                    <div class="mb-4">
                        <label for="deksripsi" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="deksripsi" id="deksripsi" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2 border"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('tambahKategoriModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div id="editKategoriModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Kategori</h3>
                <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editKategoriForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-4">
                    <div class="mb-4">
                        <label for="edit_nama_kategori" class="block text-gray-700 text-sm font-medium mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2 border" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_deksripsi" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="deksripsi" id="edit_deksripsi" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full p-2 border"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Update
                    </button>
                    <button type="button" onclick="document.getElementById('editKategoriModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editKategori(id, nama, deskripsi) {
        document.getElementById('editKategoriForm').action = `{{ url('kategori') }}/${id}`;
        document.getElementById('edit_nama_kategori').value = nama;
        document.getElementById('edit_deksripsi').value = deskripsi || '';
        document.getElementById('editKategoriModal').classList.remove('hidden');
    }
</script>
@endpush
