<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Barang Masuk') }}
        </h2>
        
        <div class="flex items-center gap-x-5">
            <form action="{{ route('barangmasuk.index') }}" method="GET" class="flex items-center max-w-sm mx-auto">   
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
    
            <a href="{{ route('barangmasuk.create') }}" class="text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-2xl text-sm w-full sm:w-auto py-2 px-3 text-center">Tambah Data</a>
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
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Serial Number
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Barang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kondisi Barang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Supplier
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Keterangan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal Masuk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            <div class="ps-3 w-4 font-medium text-gray-900">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->serial_number }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->nama_barang }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->nama_status_barang }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->nama_supplier }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->keterangan }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-base text-black">{{ $d->tanggal }}</div>
                                    </td>
                                    <td class="px-6 py-4 flex gap-x-2">
                                        <a href="/barangmasuk/delete/{{ $d->id }}"
                                            class="flex text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm px-3 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            type="button">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
