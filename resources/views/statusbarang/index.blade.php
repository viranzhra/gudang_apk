<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Status Barang') }}
        </h2>
        
        <div class="flex items-center gap-x-5">
            <form action="{{ route('statusbarang.index') }}" method="GET" class="flex items-center max-w-sm mx-auto">   
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <input type="text" id="simple-search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Cari..." required />
                </div>
                <button type="submit" class="p-2.5 ms-2 text-sm font-medium text-white bg-gray-800 rounded-2xl border border-gray-700 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </form>
    
            <a href="{{ route('statusbarang.create') }}" class="text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-2xl text-sm w-full sm:w-auto py-2 px-3 text-center">Tambah Data</a>

            <button id="delete-selected"
                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-2xl text-sm py-2 px-3 text-center hidden">
                Hapus Terpilih
            </button>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (!$data->isEmpty())
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status Barang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="w-4 px-6 py-4">
                                        <input type="checkbox" class="select-item flex justify-center items-center"
                                            value="{{ $d->id }}">
                                    </td>
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <div class="ps-3 w-4 font-medium text-gray-900">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 flex gap-x-2">
                                        <a href="/statusbarang/edit/{{ $d->id }}" type="button"
                                            class="flex text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ubah</a>

                                        <a href="/statusbarang/delete/{{ $d->id }}"
                                            class="flex text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            type="button">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <script>
                        document.getElementById('select-all').addEventListener('change', function(e) {
                            const checkboxes = document.querySelectorAll('.select-item');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = e.target.checked;
                            });
                            toggleDeleteButton();
                        });

                        document.querySelectorAll('.select-item').forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                toggleDeleteButton();
                            });
                        });

                        function toggleDeleteButton() {
                            const selected = document.querySelectorAll('.select-item:checked').length;
                            const deleteButton = document.getElementById('delete-selected');
                            if (selected > 0) {
                                deleteButton.classList.remove('hidden');
                            } else {
                                deleteButton.classList.add('hidden');
                            }
                        }

                        document.getElementById('delete-selected').addEventListener('click', function() {
                            const selected = [];
                            document.querySelectorAll('.select-item:checked').forEach(checkbox => {
                                selected.push(checkbox.value);
                            });

                            if (selected.length > 0) {
                                if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                                    fetch('/statusbarang/delete-selected', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            ids: selected
                                        })
                                    }).then(response => {
                                        if (response.ok) {
                                            location.reload();
                                        } else {
                                            alert('Gagal menghapus data.');
                                        }
                                    });
                                }
                            } else {
                                alert('Tidak ada data yang dipilih.');
                            }
                        });
                    </script>

                    <div class="py-3 px-5">
                        {{ $data->links() }}
                    </div>
                @else
                <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                    <div class="mx-auto max-w-screen-sm text-center">
                        <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600 dark:text-primary-500">404</h1>
                        <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Data tidak ditemukan.</p>
                    </div>   
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
