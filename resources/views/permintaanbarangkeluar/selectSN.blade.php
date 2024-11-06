@extends('layouts.navigation')

@section('content')
    <div class="container mt-3 shadow-sm p-4" style="border-radius: 20px;width:768px">
        <h5 style="font-weight:700;margin-bottom: 30px">Select Serial Number</h5>
    
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
                        <select id="serial_number_{{ $barangId }}_{{ $i }}" name="serial_number_ids[{{ $barangId }}][]" class="form-control select2" data-barang-id="{{ $barangId }}" required>
                            <option value="">Select SN</option>
                            @foreach ($serialNumbers as $key => $serialOption)
                                <option value="{{ $serialOption->serial_number_id }}" {{ $key === $i ? 'selected' : '' }}>{{ $serialOption->serial_number }}</option>
                            @endforeach
                        </select>
                        @error('serial_number_ids.' . $barangId . '.' . $i)
                            <span class="small" style="color:red">{{ $message }}</span>
                        @else
                            <span class="small error-message" style="color:red"></span>
                        @enderror                    
                    </div>
                    @endfor
                </div>
                <hr>
            @endforeach
    
            <div class="flex items-center justify-between">
                <!-- Tambahkan ID pada tombol Submit untuk manipulasi -->
                <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
            </div>
        </form>
    </div>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Fungsi untuk validasi duplikasi SN per produk dan validasi input kosong
            function validateForm() {
                var isValid = true;
                var barangIds = [];
                var allSelects = $('select[name^="serial_number_ids"]');

                // Kumpulkan semua barangId
                allSelects.each(function() {
                    var barangId = $(this).data('barang-id');
                    if (!barangIds.includes(barangId)) {
                        barangIds.push(barangId);
                    }
                });

                // Validasi per barangId
                barangIds.forEach(function(barangId) {
                    var selects = $('select[data-barang-id="' + barangId + '"]');
                    var values = [];
                    var duplicates = {};

                    // Hapus pesan error sebelumnya
                    selects.each(function() {
                        $(this).parent().find('.error-message').text('');
                    });

                    // Validasi setiap select input
                    selects.each(function() {
                        var val = $(this).val();
                        if (!val) {
                            // Jika ada select yang belum dipilih
                            isValid = false;
                            $(this).parent().find('.error-message').text('Please select a Serial Number.');
                        } else {
                            if (values.includes(val)) {
                                duplicates[val] = true;
                                isValid = false;
                            } else {
                                values.push(val);
                            }
                        }
                    });

                    // Tampilkan pesan error jika ada duplikasi
                    if (Object.keys(duplicates).length > 0) {
                        selects.each(function() {
                            var val = $(this).val();
                            if (duplicates[val]) {
                                $(this).parent().find('.error-message').text('Duplicate Serial Number selected.');
                            }
                        });
                    }
                });

                // Tampilkan atau sembunyikan tombol Submit
                if (isValid) {
                    $('#submitBtn').show();
                } else {
                    $('#submitBtn').hide();
                }
            }

            // Event listener untuk perubahan pada select input
            $('select[name^="serial_number_ids"]').on('change', function() {
                validateForm();
            });

            // Validasi awal saat halaman dimuat
            validateForm();
        });
    </script>
@endsection
