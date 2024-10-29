<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Barang Masuk') }}
        </h2>

        <div class="flex items-center gap-x-5">
            <form action="{{ route('laporan.barangmasuk.index') }}" method="GET"
                class="flex items-center max-w-sm mx-auto">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <input type="text" id="simple-search" name="search" value="{{ request('search') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        placeholder="Cari..." required />
                </div>
                <button type="submit"
                    class="p-2.5 ms-2 text-sm font-medium text-white bg-gray-800 rounded-2xl border border-gray-700 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </form>

            <a href="{{ route('laporan.barangmasuk.index') }}"
                class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-2xl text-sm w-full sm:w-auto py-2 px-3 text-center">Cetak</a>
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (!$data->isEmpty())
                    <form action="{{ route('laporan.barangmasuk.index') }}" method="GET" class="flex justify-end py-3 px-6 mb-4 border-1 border-b-2">
                        <div class="flex items-center space-x-3">
                            <div id="date-range-picker" date-rangepicker class="flex items-center">
                                <style>input[type="date"]::-webkit-inner-spin-button,
                                input[type="date"]::-webkit-calendar-picker-indicator {opacity:0;}</style>
                                <span class="me-4 text-gray-500">dari</span>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input id="datepicker-range-start" name="start_date" type="date"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full pe-2.5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Select date start" value="{{ $startDate ?? '' }}">
                                </div>
                                <span class="mx-4 text-gray-500">hingga</span>
                                <div class="relative">
                                    <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input id="datepicker-range-end" name="end_date" type="date"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full pe-2.5 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Select date end" value="{{ $endDate ?? '' }}">
                                </div>
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 text-white font-medium py-2 px-3 rounded-2xl">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th scope="col" class="px-6 py-3">No</th>
                                {{-- <th scope="col" class="px-6 py-3">Serial Number</th> --}}
                                <th scope="col" class="px-6 py-3">Barang</th>
                                <th scope="col" class="px-6 py-3">Jumlah</th>
                                {{-- <th scope="col" class="px-6 py-3">Kondisi Barang</th> --}}
                                <th scope="col" class="px-6 py-3">Keterangan</th>
                                <th scope="col" class="px-6 py-3">Tanggal Masuk</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr class="bg-white border-b hover:bg-gray-50 text-base text-black">
                                    <td class="w-4 px-6 py-4">
                                        <input type="checkbox" class="select-item flex justify-center items-center"
                                            value="{{ $d->barang_masuk_id }}">
                                    </td>
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                    {{-- <td class="px-6 py-4">{{ $d->serial_number }}</td> --}}
                                    <td class="px-6 py-4">{{ $d->nama_barang }}</td>
                                    <td class="px-6 py-4">{{ $d->jumlah ?: 0 }}</td>
                                    {{-- <td class="px-6 py-4"><b style="color:{{ $d->warna_status_barang }}">{{ $d->nama_status_barang }}</b></td> --}}
                                    <td class="px-6 py-4">{{ $d->keterangan }}</td>
                                    <td class="px-6 py-4">{{ $d->tanggal }}</td>
                                    <td class="px-6 py-4 flex gap-x-2">
                                        <button type="button"
                                            class="flex text-white bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-2xl text-sm px-3 py-1.5 text-center"
                                            data-bs-toggle="modal" data-bs-target="#detailModal{{ $d->barang_masuk_id }}">
                                            <svg class='line' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'
                                                stroke="white" stroke-width="2" fill="none" width="18"
                                                height="18">
                                                <g transform='translate(3.649800, 2.749900)'>
                                                    <line x1='10.6555' y1='12.6999' x2='5.2555' y2='12.6999'>
                                                    </line>
                                                    <line x1='8.6106' y1='8.6886' x2='5.2546' y2='8.6886'>
                                                    </line>
                                                    <path
                                                        d='M16.51,5.55 L10.84,0.15 C10.11,0.05 9.29,0 8.39,0 C2.1,0 -1.95399252e-14,2.32 -1.95399252e-14,9.25 C-1.95399252e-14,16.19 2.1,18.5 8.39,18.5 C14.69,18.5 16.79,16.19 16.79,9.25 C16.79,7.83 16.7,6.6 16.51,5.55 Z'>
                                                    </path>
                                                    <path
                                                        d='M10.2844,0.0827 L10.2844,2.7437 C10.2844,4.6017 11.7904,6.1067 13.6484,6.1067 L16.5994,6.1067'>
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="detailModal{{ $d->barang_masuk_id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $d->barang_masuk_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel{{ $d->barang_masuk_id }}">Detail Barang Masuk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="grid grid-cols-10 gap-2">
                                                    <div class="font-bold col-span-3">Nama Barang:</div>
                                                    <div class="col-span-7">{{ $d->nama_barang }}</div>
                                                    <div class="font-bold col-span-3">Jenis Barang:</div>
                                                    <div class="col-span-7">{{ $d->nama_jenis_barang }}</div>
                                                    <div class="font-bold col-span-3">Supplier:</div>
                                                    <div class="col-span-7">{{ $d->nama_supplier }}</div>
                                                    <div class="font-bold col-span-3">Tanggal Masuk:</div>
                                                    <div class="col-span-7">{{ $d->tanggal }}</div>
                                                    <div class="font-bold col-span-3">Keterangan:</div>
                                                    <div class="col-span-7">{{ $d->keterangan }}</div>
                                                    <div class="font-bold col-span-3">Jumlah:</div>
                                                    <div class="col-span-7">{{ $d->jumlah }}</div>

                                                    <!-- Detail barang -->
                                                    @foreach ($d->detail as $index => $detail)
                                                        <hr class="col-span-10 my-2">
                                                        <div class="font-bold col-span-3">Barang {{ $index + 1 }}:</div>
                                                        <div class="col-span-7">
                                                            <div class="font-bold">SN / Kondisi</div>
                                                            <div class="ml-5">{{ $detail->serial_number }} — {{ $detail->status_barang }}</div>
                                                            <div class="font-bold">Kelengkapan</div>
                                                            <div class="ml-5">{{ $detail->kelengkapan }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            {{-- <div class="modal-footer gap-x-3">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="py-3 px-5">
                        {{ $data->links() }}
                    </div>
                @else
                    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                        <div class="mx-auto max-w-screen-sm text-center">
                            <h1
                                class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600 dark:text-primary-500">
                                404</h1>
                            <p
                                class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">
                                Data tidak ditemukan.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
