<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permintaan Barang Keluar') }}
        </h2>
    </x-slot>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="post" action="{{ route('permintaanbarangkeluar.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-3"
                            role="alert">
                            <strong class="font-bold">Ups!</strong> Terjadi kesalahan:
                            <ul class="mt-3 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <label for="customer" class="block mb-2 text-sm font-medium text-gray-900">Penerima</label>
                            <select id="customer" name="customer_id"
                                class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option selected>Pilih penerima</option>
                                @foreach ($customer as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <label for="keperluan"
                                class="block mb-2 text-sm font-medium text-gray-900">Keperluan</label>
                            <select id="keperluan" name="keperluan_id"
                                class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option selected>Pilih keperluan</option>
                                @foreach ($keperluan as $d)
                                    <option value="{{ $d->id }}" data-extend="{{ $d->extend }}"
                                        {{-- data-tanggal-awal="{{ $d->nama_tanggal_awal }}" --}}
                                        data-tanggal-akhir="{{ $d->nama_tanggal_akhir }}">
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan"
                            class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-5">
                        <label id="label-tanggal-awal" for="tanggal_awal"
                            class="block mb-2 text-sm font-medium text-gray-900">Tanggal Permintaan</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="{{ date('Y-m-d') }}" required />
                    </div>

                    <!-- Input Tanggal Akhir (Awalnya Disembunyikan) -->
                    <div id="tanggal-akhir-container" class="mb-5 hidden">
                        <label id="label-tanggal-akhir" for="tanggal_akhir"
                            class="block mb-2 text-sm font-medium text-gray-900">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                    </div>

                    <div class="mb-5">
                        <label for="quantity-input" class="block mb-2 text-sm font-medium text-gray-900">Jumlah
                            Permintaan:</label>
                        <div class="relative flex items-center max-w-[8rem]">
                            <button type="button" id="decrement-button"
                                class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 18 2">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 1h16" />
                                </svg>
                            </button>
                            <input type="text" id="quantity-input" data-input-counter data-input-counter-min="1"
                                data-input-counter-max="100" aria-describedby="helper-text-explanation"
                                class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"
                                placeholder="1" value="1" required />
                            <button type="button" id="increment-button"
                                class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 18 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M9 1v16M1 9h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="permintaan-container">
                        <hr class="mb-5">
                        <!-- Input Permintaan Template -->
                        <div class="permintaan-input-item" data-index="1">
                            <span class="block text-sm font-medium text-gray-900 mb-5">Permintaan 1</span>
                            <div class="mb-5">
                                <label for="jenis_barang_1" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                                    Barang</label>
                                <select id="jenis_barang_1" name="jenis_barang_ids[]"
                                    class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected>Pilih jenis barang</option>
                                    @foreach ($jenis_barang as $d)
                                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-5">
                                <label for="barang_1"
                                    class="block mb-2 text-sm font-medium text-gray-900">Barang</label>
                                <select id="barang_1" name="barang_ids[]"
                                    class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected>Pilih barang</option>
                                </select>
                                <span id="stok_1" class="flex mt-2 text-sm text-gray-600">Stok Tersedia: 0</span>
                            </div>
                            <div id="head_jumlah_barang_1" class="mb-5 hidden">
                                <label for="jumlah_barang_1"
                                    class="block mb-2 text-sm font-medium text-gray-900">Jumlah:</label>
                                <div class="relative flex items-center max-w-[8rem]">
                                    <button type="button" id="decrement-button-barang-1"
                                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                        </svg>
                                    </button>
                                    <input type="text" id="jumlah_barang_1" name="jumlah_barangs[]" data-input-counter
                                        data-input-counter-min="1" data-input-counter-max="100"
                                        aria-describedby="helper-text-explanation"
                                        class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"
                                        placeholder="1" value="1" required />
                                    <button type="button" id="increment-button-barang-1"
                                        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                        <svg class="w-3 h-3 text-gray-900" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>

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
                            const namaTanggalAwal = selectedOption.getAttribute('data-tanggal-awal');
                            const namaTanggalAkhir = selectedOption.getAttribute('data-tanggal-akhir');

                            if (extend == 1) {
                                tanggalAkhirContainer.classList.remove('hidden');
                                labelTanggalAwal.textContent = namaTanggalAwal;
                                labelTanggalAkhir.textContent = namaTanggalAkhir;
                            } else {
                                tanggalAkhirContainer.classList.add('hidden');
                                labelTanggalAwal.textContent = 'Tanggal Permintaan';
                                labelTanggalAkhir.textContent = 'Tanggal Akhir';
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
                    <hr class="mb-5">
                    <span class="block text-sm font-medium text-gray-900 mb-5">Permintaan ${index}</span>
                    <div class="mb-5">
                        <label for="jenis_barang_${index}"
                            class="block mb-2 text-sm font-medium text-gray-900">Jenis Barang</label>
                        <select id="jenis_barang_${index}" name="jenis_barang_ids[]"
                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih jenis barang</option>
                            @foreach ($jenis_barang as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="barang_${index}" class="block mb-2 text-sm font-medium text-gray-900">Barang</label>
                        <select id="barang_${index}" name="barang_ids[]"
                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih barang</option>
                        </select>
                        <span id="stok_${index}" class="flex mt-2 text-sm text-gray-600">Stok Tersedia: 0</span>
                    </div>

                    <div id="head_jumlah_barang_${index}" class="mb-5 hidden">                                 
                        <label for="jumlah_barang_${index}"                                     
                            class="block mb-2 text-sm font-medium text-gray-900">Jumlah:</label>                                 
                        <div class="relative flex items-center max-w-[8rem]">                                     
                            <button type="button" id="decrement-button-barang-${index}"                                         
                                class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">                                         
                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true"                                             
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">                                             
                                    <path stroke="currentColor" stroke-linecap="round"                                                 
                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />                                         
                                </svg>                                     
                            </button>                                     
                            <input type="text" id="jumlah_barang_${index}" name="jumlah_barangs[]" data-input-counter                                         
                                data-input-counter-min="1" data-input-counter-max="100"                                         
                                aria-describedby="helper-text-explanation"                                         
                                class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"                                         
                                placeholder="1" value="1" required />                                     
                            <button type="button" id="increment-button-barang-${index}"                                         
                                class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">                                         
                                <svg class="w-3 h-3 text-gray-900" aria-hidden="true"                                             
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">                                             
                                    <path stroke="currentColor" stroke-linecap="round"                                                 
                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />                                         
                                </svg>                                     
                            </button>                                 
                        </div>                             
                    </div>
                `;
                
                // Move the script outside of the innerHTML
                var script = document.createElement('script');
                script.textContent = `
                    // Increment & Decrement for barang quantity input
                    var quantityInputBarang = document.getElementById('jumlah_barang_${index}');
                    var incrementButtonBarang = document.getElementById('increment-button-barang-${index}');
                    var decrementButtonBarang = document.getElementById('decrement-button-barang-${index}');

                    incrementButtonBarang.addEventListener('click', function() {
                        let currentValueBarang = parseInt(quantityInputBarang.value);
                        var maxValueBarang = parseInt(quantityInputBarang.getAttribute(
                            'data-input-counter-max')) || 100;
                        if (currentValueBarang < maxValueBarang) {
                            quantityInputBarang.value = currentValueBarang + 1;
                        }
                    });

                    decrementButtonBarang.addEventListener('click', function() {
                        let currentValueBarang = parseInt(quantityInputBarang.value);
                        var minValueBarang = parseInt(quantityInputBarang.getAttribute(
                            'data-input-counter-min')) || 1;
                        if (currentValueBarang > minValueBarang) {
                            quantityInputBarang.value = currentValueBarang - 1;
                        }
                    });
                `;
                
                newItem.appendChild(script);
                
                return newItem;
            }

            // Increment & Decrement for barang quantity input
            const quantityInputBarang = document.getElementById('jumlah_barang_1');
            const incrementButtonBarang = document.getElementById('increment-button-barang-1');
            const decrementButtonBarang = document.getElementById('decrement-button-barang-1');

            incrementButtonBarang.addEventListener('click', function() {
                let currentValueBarang = parseInt(quantityInputBarang.value);
                const maxValueBarang = parseInt(quantityInputBarang.getAttribute(
                    'data-input-counter-max')) || 100;
                if (currentValueBarang < maxValueBarang) {
                    quantityInputBarang.value = currentValueBarang + 1;
                }
            });

            decrementButtonBarang.addEventListener('click', function() {
                let currentValueBarang = parseInt(quantityInputBarang.value);
                const minValueBarang = parseInt(quantityInputBarang.getAttribute(
                    'data-input-counter-min')) || 1;
                if (currentValueBarang > minValueBarang) {
                    quantityInputBarang.value = currentValueBarang - 1;
                }
            });

            function initializeSelect2() {
                $('.select2').select2();

                $('.select2').on('select2:select', function(e) {
                    const selectElement = $(this);
                    const index = selectElement.attr('id').split('_').pop(); // Get the index from id

                    const id = selectElement.attr('id');
                    if (id === 'keperluan') {
                        const selectedOption = selectElement.find(':selected');
                        const extend = selectedOption.data('extend');
                        const namaTanggalAwal = selectedOption.data('tanggal-awal');
                        const namaTanggalAkhir = selectedOption.data('tanggal-akhir');

                        // Menampilkan atau menyembunyikan input tanggal akhir
                        if (extend == 1) {
                            $('#tanggal-akhir-container').removeClass('hidden');
                            $('#label-tanggal-awal').text(namaTanggalAwal);
                            $('#label-tanggal-akhir').text(namaTanggalAkhir);
                        } else {
                            $('#tanggal-akhir-container').addClass('hidden');
                            $('#label-tanggal-awal').text('Tanggal Permintaan');
                            $('#label-tanggal-akhir').text('Tanggal Akhir');
                        }
                    }

                    if (selectElement.attr('id').startsWith('jenis_barang')) {
                        const jenisBarangId = e.params.data.id;
                        fetch(`/permintaanbarangkeluar/get-by-jenis/${jenisBarangId}`)
                            .then(response => response.json())
                            .then(data => {
                                const barangSelect = $(`#barang_${index}`);
                                barangSelect.empty();
                                barangSelect.append('<option selected>Pilih barang</option>');
                                data.forEach(barang => {
                                    const option = new Option(barang.nama, barang.id, false,
                                        false);
                                    barangSelect.append(option);
                                });
                                barangSelect.trigger('change');

                                $(`#stok_${index}`).text(`Stok Tersedia: 0`);
                                $(`#jumlah_barang_${index}`).attr('data-input-counter-max', 1);
                                $(`#jumlah_barang_${index}`).val(1);
                                $(`#head_jumlah_barang_${index}`).hide();
                            })
                            .catch(error => console.error('Error fetching barang:', error));
                    }

                    if (selectElement.attr('id').startsWith('barang')) {
                        const barangId = e.params.data.id;
                        fetch(`/permintaanbarangkeluar/get-stok/${barangId}`)
                            .then(response => response.json())
                            .then(data => {
                                $(`#stok_${index}`).text(`Stok Tersedia: ${data.stok}`);
                                if (data.stok > 0) {
                                    $(`#jumlah_barang_${index}`).attr('data-input-counter-max', data.stok);
                                    $(`#jumlah_barang_${index}`).val(1);
                                    $(`#head_jumlah_barang_${index}`).show();
                                } else {
                                    $(`#head_jumlah_barang_${index}`).hide();
                                }
                            })
                            .catch(error => console.log('Error fetching stok:', error));
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
                    initializeSelect2(); // Re-initialize Select2 for new elements
                }

                // Remove extra inputs if needed
                if (quantity < itemCount) {
                    for (let i = itemCount; i > quantity; i--) {
                        container.removeChild(container.querySelector(`.permintaan-input-item[data-index="${i}"]`));
                    }
                }
            }

            incrementButton.addEventListener('click', function() {
                if (parseInt(quantityInput.value, 10) < parseInt(quantityInput.dataset.inputCounterMax,
                        10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) + 1;
                    updatePermintaanInputs();
                }
            });

            decrementButton.addEventListener('click', function() {
                if (parseInt(quantityInput.value, 10) > parseInt(quantityInput.dataset.inputCounterMin,
                        10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) - 1;
                    updatePermintaanInputs();
                }
            });

            container.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-permintaan-button')) {
                    const item = event.target.closest('.permintaan-input-item');
                    container.removeChild(item);
                    // Update the remaining items' indexes
                    Array.from(container.getElementsByClassName('permintaan-input-item')).forEach((elem,
                        idx) => {
                        elem.setAttribute('data-index', idx + 1);
                        elem.querySelector('span').textContent = `Permintaan ${idx + 1}`;
                        elem.querySelector('select').id = `jenis_barang_${idx + 1}`;
                        elem.querySelector('select').id = `barang_${idx + 1}`;
                        elem.querySelector('select').id = `serialnumber_${idx + 1}`;
                    });
                    quantityInput.value = container.getElementsByClassName('permintaan-input-item').length;
                }
            });

            // Initialize items based on the default quantity
            updatePermintaanInputs();
            initializeSelect2();
        });
    </script>

</x-app-layout>
