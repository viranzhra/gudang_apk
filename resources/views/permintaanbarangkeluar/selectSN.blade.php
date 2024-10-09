@extends('layouts.navigation')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<style>
    .select2-container .select2-selection--single {
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
        border-radius: .5rem;
        height: 40px;
        display: flex;
        align-items: center
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 10px
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: unset;
        right: 10px
    }
</style>
    <div class="container mt-3 shadow-sm p-4" style="border-radius: 20px;width:768px">
        <h5 style="font-weight:700;margin-bottom: 30px">Pilih Serial Number</h5>
    
        <form action="{{ route('permintaanbarangkeluar.setSN') }}" method="POST">
            @csrf
            <input type="hidden" name="permintaan_id" value="{{ $id }}">
    
            @foreach ($groupedSerialNumbers as $barangId => $serialNumbers)
                <div class="mb-4">
                    <label class="form-label" style="font-size: 1.1em"><b>{{ $serialNumbers[0]->nama_barang }}</b></label>
                    <br>
                    @for ($i = 0; $i < $serialNumbers[0]->jumlah; $i++)
                    <div class="ps-3 mb-2">
                        <label for="serial_number_{{ $barangId }}_{{ $i }}" class="form-label">Serial Number {{ $i + 1 }}</label>
                        <select id="serial_number_{{ $barangId }}_{{ $i }}" name="serial_number_ids[{{ $barangId }}][]" class="form-control select2">
                            <option value="">Pilih Serial Number</option>
                            @foreach ($serialNumbers as $serialOption)
                                <option value="{{ $serialOption->serial_number_id }}">{{ $serialOption->serial_number }}</option>
                            @endforeach
                        </select>
                        @error('serial_number_ids.' . $barangId . '.' . $i)
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @endfor
                </div>
                <hr>
            @endforeach
    
            <div class="flex items-center justify-between">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    
@endsection