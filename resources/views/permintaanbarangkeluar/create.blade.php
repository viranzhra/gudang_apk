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

        /* Styling untuk pesan error */
        .error-message {
            color: #dc2626; /* Warna merah */
            font-size: 0.875rem; /* Ukuran font kecil */
            margin-top: 0.25rem;
            display: block;
        }
    </style>
    <div class="container mt-3 shadow-sm p-4" style="border-radius: 20px;width:768px">

        <h5 style="font-weight:700;margin-bottom: 30px">Create Request</h5>
        @can('item request.create')
        <form method="post" action="{{ route('permintaanbarangkeluar.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3" role="alert">
                    <strong class="font-bold">Ups!</strong> Terjadi kesalahan:
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
 
            <div class="grid md:grid-cols-2 md:gap-6">
                @canall(['item request.create', 'item request.viewAll', 'item request.confirm'])
                <div class="relative z-0 w-full mb-3 group">
                    <label for="customer" class="form-label">Customer</label>
                        <select id="customer" name="customer_id"
                        class="select2 form-control">
                        <option value="">Select customer</option>
                        @foreach ($customer as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                        @endforeach
                        </select>
                    @error('customer_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                @endcanall
                <div class="relative z-0 w-full mb-3 group">
                    <label for="keperluan" class="form-label">Purpose</label>
                    <select id="keperluan" name="keperluan_id"
                        class="select2 form-control">
                        <option value="">Select purpose</option>
                        @foreach ($keperluan as $d)
                            <option value="{{ $d->id }}" data-extend="{{ $d->extend }}" data-batas_hari="{{ $d->batas_hari }}" {{-- data-tanggal-awal="{{ $d->nama_tanggal_awal }}" --}}
                                data-tanggal-akhir="{{ $d->nama_tanggal_akhir }}">
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('keperluan_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Description</label>
                <textarea type="text" id="keterangan" name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label id="label-tanggal-awal" for="tanggal_awal" class="form-label">Request Date</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="{{ old('tanggal_awal', date('Y-m-d')) }}" disabled />
                @error('tanggal_awal')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Tanggal Akhir (Awalnya Disembunyikan) -->
            <div id="tanggal-akhir-container" class="mb-3 d-none">
                <label id="label-tanggal-akhir" for="tanggal_akhir" class="form-label">End Date</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="{{ old('tanggal_akhir', date('Y-m-d', strtotime('+1 days')) ) }}" min="{{ date('Y-m-d', strtotime('+1 days')) }}" max="{{ date('Y-m-d', strtotime('+90 days')) }}" />
                @error('tanggal_akhir')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="quantity-input" class="form-label">Qty:</label>
                <div class="input-group" style="max-width: 20%;margin-top:8px">
                    <button type="button" id="decrement-button" class="button-minus border rounded-circle icon-shape icon-sm mx-1 lh-0">
                        <iconify-icon icon="bi:dash" width="18" height="18"></iconify-icon>
                    </button>
                    <input type="text" id="quantity-input" class="pe-1 quantity-field border-0 text-center w-25" data-input-counter data-input-counter-min="1"
                    data-input-counter-max="100" aria-describedby="helper-text-explanation" placeholder="1" value="{{ old('quantity', 1) }}" required>
                    <button type="button" id="increment-button" class="button-plus border rounded-circle icon-shape icon-sm lh-0">
                        <iconify-icon icon="bi:plus" width="18" height="18"></iconify-icon>
                    </button>
                </div>
                @error('quantity')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div id="permintaan-container">
                <hr class="mb-4">
                <!-- Input Permintaan Template -->
                <div class="permintaan-input-item" data-index="1">
                    <h6 class="mb-3">Request 1</h6>
                    <div class="mb-3">
                        <label for="jenis_barang_1" class="form-label">Item Type</label>
                        <select id="jenis_barang_1" name="jenis_barang_ids[]" class="select2 form-control">
                            <option value="">Select item type</option>
                            @foreach ($jenis_barang as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_barang_ids.0')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="barang_1" class="form-label">Item</label>
                        <select id="barang_1" name="barang_ids[]"
                            class="select2 form-control">
                            <option value="">Select item</option>
                        </select>
                        <!-- Tambahkan elemen span untuk pesan error -->
                        <span id="error_barang_1" class="error-message"></span>
                        <span id="stok_1" class="d-flex mt-3 text-sm text-gray-600">Available Stock: 0</span>
                        @error('barang_ids.0')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="head_jumlah_barang_1" class="mb-5 d-none">
                        <label for="jumlah_barang_1" class="form-label">Qty:</label>
                        <div class="input-group" style="max-width: 20%;margin-top:8px">
                            <button type="button" id="decrement-button-barang-1" class="button-minus border rounded-circle icon-shape icon-sm mx-1 lh-0">
                                <iconify-icon icon="bi:dash" width="18" height="18"></iconify-icon>
                            </button>

                            <input type="text" id="jumlah_barang_1" name="jumlah_barangs[]" data-input-counter data-input-counter-min="1" data-input-counter-max="100" aria-describedby="helper-text-explanation" class="pe-1 quantity-field border-0 text-center w-25" placeholder="1" value="1" required />
                            <button type="button" id="increment-button-barang-1" class="button-plus border rounded-circle icon-shape icon-sm lh-0">
                                <iconify-icon icon="bi:plus" width="18" height="18"></iconify-icon>
                            </button>
                        </div>
                        @error('jumlah_barangs.0')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary" id="submitBtn">Send</button>

        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const keperluanSelect = document.getElementById('keperluan');
                const tanggalAkhirContainer = document.getElementById('tanggal-akhir-container');
                const labelTanggalAwal = document.getElementById('label-tanggal-awal');
                const labelTanggalAkhir = document.getElementById('label-tanggal-akhir');
                const tanggalAwalInput = document.getElementById('tanggal_awal');
                const tanggalAkhirInput = document.getElementById('tanggal_akhir');

                keperluanSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const extend = selectedOption.getAttribute('data-extend');
                    const batas_hari = selectedOption.getAttribute('data-batas_hari');
                    const namaTanggalAwal = selectedOption.getAttribute('data-tanggal-awal');
                    const namaTanggalAkhir = selectedOption.getAttribute('data-tanggal-akhir');

                    if (extend == 1) {
                        tanggalAkhirContainer.classList.remove('d-none');
                        labelTanggalAwal.textContent = namaTanggalAwal || 'Tanggal Permintaan';
                        labelTanggalAkhir.textContent = namaTanggalAkhir || 'Tanggal Akhir';
                    } else {
                        tanggalAkhirContainer.classList.add('d-none');
                        labelTanggalAwal.textContent = 'Tanggal Permintaan';
                        labelTanggalAkhir.textContent = 'Tanggal Akhir';
                    }
                });
            });
        </script>
        @endcan

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('permintaan-container');
            const quantityInput = document.getElementById('quantity-input');
            const incrementButton = document.getElementById('increment-button');
            const decrementButton = document.getElementById('decrement-button');
            let currentIndex = 1;

            function createPermintaanInput(index) {
                const newItem = document.createElement('div');
                newItem.classList.add('permintaan-input-item');
                newItem.setAttribute('data-index', index);
                newItem.innerHTML = `
                    <hr class="mb-4">
                    <h6 class="mb-3">Request ${index}</h6>
                    <div class="mb-3">
                        <label for="jenis_barang_${index}" class="form-label">Item Type</label>
                        <select id="jenis_barang_${index}" name="jenis_barang_ids[]" class="select2 form-control">
                            <option value="">Select item type</option>
                            @foreach ($jenis_barang as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_barang_ids.${index}')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="barang_${index}" class="form-label">Item</label>
                        <select id="barang_${index}" name="barang_ids[]"
                            class="select2 form-control">
                            <option value="">Select item</option>
                        </select>
                        <!-- Tambahkan elemen span untuk pesan error -->
                        <span id="error_barang_${index}" class="error-message"></span>
                        <span id="stok_${index}" class="d-flex mt-3 text-sm text-gray-600">Available Stock: 0</span>
                        @error('barang_ids.${index}')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="head_jumlah_barang_${index}" class="mb-5 d-none">
                        <label for="jumlah_barang_${index}" class="form-label">Qty:</label>
                        <div class="input-group" style="max-width: 20%;margin-top:8px">
                            <button type="button" id="decrement-button-barang-${index}" class="button-minus border rounded-circle icon-shape icon-sm mx-1 lh-0">
                                <iconify-icon icon="bi:dash" width="18" height="18"></iconify-icon>
                            </button>

                            <input type="text" id="jumlah_barang_${index}" name="jumlah_barangs[]" data-input-counter data-input-counter-min="1" data-input-counter-max="100" aria-describedby="helper-text-explanation" class="pe-1 quantity-field border-0 text-center w-25" placeholder="1" value="1" required />
                            <button type="button" id="increment-button-barang-${index}" class="button-plus border rounded-circle icon-shape icon-sm lh-0">
                                <iconify-icon icon="bi:plus" width="18" height="18"></iconify-icon>
                            </button>
                        </div>
                        @error('jumlah_barangs.${index}')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                `;

                var script = document.createElement('script');
                script.textContent = `
                    // Increment & Decrement for barang quantity input
                    var quantityInputBarang = document.getElementById('jumlah_barang_${index}');
                    var incrementButtonBarang = document.getElementById('increment-button-barang-${index}');
                    var decrementButtonBarang = document.getElementById('decrement-button-barang-${index}');

                    incrementButtonBarang.addEventListener('click', function() {
                        let currentValueBarang = parseInt(quantityInputBarang.value);
                        var maxValueBarang = parseInt(quantityInputBarang.getAttribute('data-input-counter-max')) || 100;
                        if (currentValueBarang < maxValueBarang) {
                            quantityInputBarang.value = currentValueBarang + 1;
                        }
                    });

                    decrementButtonBarang.addEventListener('click', function() {
                        let currentValueBarang = parseInt(quantityInputBarang.value);
                        var minValueBarang = parseInt(quantityInputBarang.getAttribute('data-input-counter-min')) || 1;
                        if (currentValueBarang > minValueBarang) {
                            quantityInputBarang.value = currentValueBarang - 1;
                        }
                    });
                `;

                newItem.appendChild(script);

                return newItem;
            }

            // Increment & Decrement for main quantity input
            const quantityInputBarang = document.getElementById('jumlah_barang_1');
            const incrementButtonBarang = document.getElementById('increment-button-barang-1');
            const decrementButtonBarang = document.getElementById('decrement-button-barang-1');

            incrementButtonBarang.addEventListener('click', function() {
                let currentValueBarang = parseInt(quantityInputBarang.value);
                const maxValueBarang = parseInt(quantityInputBarang.getAttribute('data-input-counter-max')) || 100;
                if (currentValueBarang < maxValueBarang) {
                    quantityInputBarang.value = currentValueBarang + 1;
                }
            });

            decrementButtonBarang.addEventListener('click', function() {
                let currentValueBarang = parseInt(quantityInputBarang.value);
                const minValueBarang = parseInt(quantityInputBarang.getAttribute('data-input-counter-min')) || 1;
                if (currentValueBarang > minValueBarang) {
                    quantityInputBarang.value = currentValueBarang - 1;
                }
            });

            function initializeSelect2(index) {
                $(`#jenis_barang_${index}`).select2();
                $(`#barang_${index}`).select2();

                // Tambahkan event listener pada select barang
                $(`#barang_${index}`).on('change', function() {
                    validateDuplicateItems();
                });

                $('.select2').on('select2:select', function(e) {
                    const selectElement = $(this);
                    const index = selectElement.attr('id').split('_').pop();
                    const id = selectElement.attr('id');

                    if (id === 'keperluan') {
                        const selectedOption = selectElement.find(':selected');
                        const extend = selectedOption.data('extend');
                        const batas_hari = String(selectedOption.data('batas_hari')) || 90;
                        let batas_hari_fix = new Date();
                        batas_hari_fix.setDate(batas_hari_fix.getDate() + parseInt(batas_hari));
                        $('#tanggal_akhir').prop('max', batas_hari_fix.toISOString().split('T')[0]); 
                        $('#tanggal_akhir').val(new Date(new Date().getTime() + 86400000).toISOString().split('T')[0]);

                        const namaTanggalAwal = selectedOption.data('tanggal-awal');
                        const namaTanggalAkhir = selectedOption.data('tanggal-akhir');

                        // Menampilkan atau menyembunyikan input tanggal akhir
                        if (extend == 1) {
                            $('#tanggal-akhir-container').removeClass('d-none');
                            $('#label-tanggal-awal').text(namaTanggalAwal || 'Tanggal Permintaan');
                            $('#label-tanggal-akhir').text(namaTanggalAkhir || 'Tanggal Akhir');
                        } else {
                            $('#tanggal-akhir-container').addClass('d-none');
                            $('#label-tanggal-awal').text('Tanggal Permintaan');
                            $('#label-tanggal-akhir').text('Tanggal Akhir');
                        }
                    }

                    // Jenis Barang
                    if (id.startsWith('jenis_barang')) {
                        const jenisBarangId = e.params.data.id;
                        const barangSelect = $(`#barang_${index}`);
                        const stokElement = $(`#stok_${index}`);
                        const jumlahBarangElement = $(`#jumlah_barang_${index}`);
                        const headJumlahBarangElement = $(`#head_jumlah_barang_${index}`);

                        selectElement.prop('disabled', true);
                        barangSelect.prop('disabled', true);

                        fetch(`/permintaanbarangkeluar/get-by-jenis/${jenisBarangId}`)
                            .then(response => response.json())
                            .then(data => {
                                barangSelect.empty().append('<option value="">Select item</option>');
                                data.forEach(barang => {
                                    barangSelect.append(new Option(barang.nama, barang.id, false, false));
                                });
                                barangSelect.trigger('change');
                                stokElement.text('Available Stock: 0');
                                jumlahBarangElement.attr('data-input-counter-max', 1).val(1);
                                headJumlahBarangElement.hide();
                            })
                            .catch(error => console.error('Error fetching barang:', error))
                            .finally(() => {
                                selectElement.prop('disabled', false);
                                barangSelect.prop('disabled', false);
                                validateDuplicateItems(); // Validasi setelah pengisian barang
                            });
                    }

                    // Barang
                    if (id.startsWith('barang')) {
                        const barangId = e.params.data.id;
                        const jenisBarangElement = $(`#jenis_barang_${index}`);
                        const stokElement = $(`#stok_${index}`);
                        const jumlahBarangElement = $(`#jumlah_barang_${index}`);
                        const headJumlahBarangElement = $(`#head_jumlah_barang_${index}`);

                        selectElement.prop('disabled', true);
                        jenisBarangElement.prop('disabled', true);
                        stokElement.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                        headJumlahBarangElement.hide();

                        fetch(`/permintaanbarangkeluar/get-stok/${barangId}`)
                            .then(response => response.json())
                            .then(data => {
                                stokElement.text(`Available Stock: ${data.stok}`).hide().fadeIn(300);
                                if (data.stok > 0) {
                                    jumlahBarangElement.attr('data-input-counter-max', data.stok).val(1);
                                    headJumlahBarangElement.hide().fadeIn(300).removeClass('d-none');
                                } else {
                                    headJumlahBarangElement.hide();
                                }
                            })
                            .catch(error => console.error('Error fetching stok:', error))
                            .finally(() => {
                                selectElement.prop('disabled', false);
                                jenisBarangElement.prop('disabled', false);
                                validateDuplicateItems(); // Validasi setelah pengisian stok
                            });
                    }
                });
            }

            function updatePermintaanInputs() {
                const quantity = parseInt(quantityInput.value, 10);
                const items = container.getElementsByClassName('permintaan-input-item');
                const itemCount = items.length;

                // Add new inputs if needed
                if (quantity > itemCount) {
                    for (let i = itemCount + 1; i <= quantity; i++) {
                        container.appendChild(createPermintaanInput(i));
                    }
                    setTimeout(() => {
                        initializeSelect2(quantity);
                    }, 100);
                }

                // Remove extra inputs if needed
                if (quantity < itemCount) {
                    for (let i = itemCount; i > quantity; i--) {
                        container.removeChild(container.querySelector(`.permintaan-input-item[data-index="${i}"]`));
                    }
                }

                validateDuplicateItems(); // Validasi setelah penambahan/mengurangi permintaan
            }

            incrementButton.addEventListener('click', function() {
                if (parseInt(quantityInput.value, 10) < parseInt(quantityInput.dataset.inputCounterMax, 10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) + 1;
                    updatePermintaanInputs();
                }
            });

            decrementButton.addEventListener('click', function() {
                if (parseInt(quantityInput.value, 10) > parseInt(quantityInput.dataset.inputCounterMin, 10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) - 1;
                    updatePermintaanInputs();
                }
            });

            container.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-permintaan-button')) {
                    const item = event.target.closest('.permintaan-input-item');
                    container.removeChild(item);
                    // Update the remaining items' indexes
                    Array.from(container.getElementsByClassName('permintaan-input-item')).forEach((elem, idx) => {
                        const newIndex = idx + 1;
                        elem.setAttribute('data-index', newIndex);
                        elem.querySelector('h6').textContent = `Request ${newIndex}`;
                        elem.querySelectorAll('select').forEach(select => {
                            const oldId = select.getAttribute('id');
                            const parts = oldId.split('_');
                            const type = parts[0];
                            select.setAttribute('id', `${type}_${newIndex}`);
                        });
                        elem.querySelectorAll('span[id^="error_barang_"]').forEach(span => {
                            const oldId = span.getAttribute('id');
                            const parts = oldId.split('_');
                            const type = parts[0];
                            span.setAttribute('id', `${type}_${newIndex}`);
                        });
                    });
                    quantityInput.value = container.getElementsByClassName('permintaan-input-item').length;
                    validateDuplicateItems(); // Validasi setelah penghapusan permintaan
                }
            });

            // Fungsi validasi duplikasi Item
            function validateDuplicateItems() {
                var selectedItems = {};
                var isValid = true;

                $('select[name="barang_ids[]"]').each(function() {
                    var itemId = $(this).val();
                    var index = $(this).attr('id').split('_').pop();

                    // Hapus pesan error sebelumnya
                    $(`#error_barang_${index}`).text('');

                    if (itemId && itemId.trim() !== "") { // Pastikan itemId tidak kosong
                        if (selectedItems[itemId]) {
                            // Item sudah dipilih sebelumnya, tampilkan pesan error
                            $(`#error_barang_${index}`).text('This item has already been selected.');
                            isValid = false;
                        } else {
                            selectedItems[itemId] = true;
                        }
                    }
                });

                // Kontrol visibilitas tombol Submit
                const submitBtn = document.getElementById('submitBtn');
                if (isValid && allItemsSelected()) {
                    submitBtn.style.display = 'block';
                } else {
                    submitBtn.style.display = 'none';
                }

                return isValid;
            }

            // Fungsi untuk memeriksa apakah semua item telah dipilih
            function allItemsSelected() {
                var allSelected = true;

                $('select[name="barang_ids[]"]').each(function() {
                    var itemId = $(this).val();
                    if (!itemId || itemId.trim() === "") {
                        allSelected = false;
                    }
                });

                return allSelected;
            }

            // Event listener pada form submit
            $('form').on('submit', function(e) {
                if (!validateDuplicateItems()) {
                    e.preventDefault();
                    alert('Please fix the duplicate Items before submitting the form.');
                }
            });

            // Initialize items based on the default quantity
            updatePermintaanInputs();
            // $('.select2').select2();

            if ($('#customer').length) {
                $('#customer').select2();
            }

            if ($('#keperluan').length) {
                $('#keperluan').select2();
            }

            initializeSelect2(1);

            // Validasi awal saat halaman dimuat
            validateDuplicateItems();
        });
    </script>

@endsection
