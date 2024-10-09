@extends('layouts.navigation')

@section('content')
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Pilih Serial Number</h1>
    
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="permintaan_id" value="{{ $id }}">
    
            @foreach ($groupedSerialNumbers as $barangId => $serialNumbers)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Barang: {{ $serialNumbers[0]->nama_barang }}</label>
                    <input type="text" value="{{ $serialNumbers[0]->nama_barang }}" disabled class="block w-full mt-1 rounded-md border-gray-300 shadow-sm bg-gray-200" />
    
                    @foreach ($serialNumbers as $index => $serial)
                        <label for="serial_number_{{ $barangId }}_{{ $index }}" class="block text-sm font-medium text-gray-700 mt-2">Pilih Serial Number {{ $index + 1 }}</label>
                        <select id="serial_number_{{ $barangId }}_{{ $index }}" name="serial_number_ids[{{ $barangId }}][]" class="form-select mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Serial Number</option>
                            <option value="{{ $serial->id }}">{{ $serial->serial_number }}</option>
                        </select>
                        @error('serial_number_ids.' . $barangId . '.' . $index)
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    @endforeach
                </div>
            @endforeach
    
            <div class="flex items-center justify-between">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim
                </button>
            </div>
        </form>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     // Inisialisasi Select2
        //     $('select').select2();
    
        //     // Ketika memilih barang, ambil serial number yang sesuai
        //     $('select[id^="serial_number_"]').each(function(index) {
        //         const barangId = $(this).attr('id').split('_')[1]; // ambil ID barang dari id dropdown
                
        //         $(this).change(function() {
        //             var serialNumberSelect = $(this);
        //             serialNumberSelect.empty().append('<option value="">Pilih Serial Number</option>');
    
        //             if (barangId) {
        //                 $.get('/selectSN/' + barangId, function(data) {
        //                     data.forEach(function(serial) {
        //                         serialNumberSelect.append('<option value="' + serial.id + '">' + serial.serial_number + '</option>');
        //                     });
        //                 });
        //             }
        //         });
        //     });
        // });
    </script>
    

@endsection
