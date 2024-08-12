<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f4f7fb;
        }

        .card {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Bayangan lembut untuk card */
            border-radius: 8px;
            background-color: #ffffff; /* Latar belakang putih untuk card */
            margin: auto; /* Pusatkan card secara horizontal */
            width: 90%; /* Lebar card untuk layar besar */
            max-width: 600px; /* Lebar maksimum untuk card */
        }

        .form-label {
            font-weight: bold; /* Menebalkan teks label form */
        }

        @media (max-width: 576px) {
            .card {
                width: 95%; /* Lebar card untuk layar kecil */
            }
        }

        /* Wrapper untuk menyelaraskan kontrol jumlah dengan label dan teks penjelasan */
        .quantity-control-wrapper {
            display: flex;
            align-items: center; /* Menyelaraskan item secara vertikal di tengah */
        }

        /* Kontainer Flexbox untuk elemen kontrol jumlah */
        .quantity-control {
            display: flex;
            align-items: center; /* Menyelaraskan item secara vertikal di tengah */
            gap: 0.5rem; /* Jarak antara tombol dan input */
        }

        /* Styling untuk tombol */
        .quantity-control button {
            width: 40px; /* Menyesuaikan lebar tombol */
            height: 40px; /* Menyesuaikan tinggi tombol */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px; /* Ukuran ikon */
            color: black; /* Warna ikon */
        }

        /* Styling untuk field input */
        .quantity-control input {
            width: 80px; /* Lebar field input */
            height: 40px; /* Tinggi field input */
            text-align: center; /* Menyelaraskan teks di dalam field input ke tengah */
        }

        /* Opsional: margin untuk memisahkan teks penjelasan dari kontrol */
        #helper-text-explanation {
            margin-top: 0.5rem;
        }

        .heading-with-icon {
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Center items vertically */
            margin-bottom: 30px;
            font-size: 1.25rem; /* Adjust font size as needed */
        }

        .back-icon {
            width: 24px; /* Adjust the size of the icon */
            height: 24px; /* Ensure the icon maintains aspect ratio */
            margin-right: 30%; /* Space between icon and text */
            cursor: pointer; /* Pointer cursor on hover */
            display: block; /* Ensure the icon behaves like a block element */
        }

    </style>
</head>

<body>
    <div class="py-12 d-flex justify-content-center align-items-center min-vh-100">
        <div class="container" style="padding: 30px; max-width: 690px;">
            <div class="card p-4">
                <form method="post" action="{{ route('barangmasuk.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <strong>Ups!</strong> Terjadi kesalahan:
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h5 class="heading-with-icon">
                        <img src="{{ asset('assets/images/icon/back-arrow2.png') }}" class="back-icon" onclick="history.back()" alt="Back">
                        Tambah Barang
                    </h5>                                                       
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis_barang" class="form-label">Jenis Barang</label>
                            <select id="jenis_barang" name="jenis_barang_id" class="form-select select2">
                                <option selected>Pilih jenis barang</option>
                                @foreach ($jenis_barang as $d)
                                    <option value="{{ $d->id }}" {{ $d->id == $jenis_barang_id ? 'selected' : '' }}>
                                        {{ $d->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="barang" class="form-label">Barang</label>
                            <select id="barang" name="barang_id" class="form-select select2">
                                <option selected>Pilih barang</option>
                                @if (isset($barangMasuk))
                                    @foreach ($barangbyjenis as $d)
                                        <option value="{{ $d->id }}" {{ $d->id == $barangMasuk->barang_id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" id="keterangan" name="keterangan" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal Masuk</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="quantity-input" class="form-label">Jumlah Barang:</label>
                        <div class="quantity-control-wrapper">
                            <div class="quantity-control">
                                <button type="button" id="decrement-button" class="btn btn-outline-secondary">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="text" id="quantity-input" data-input-counter data-input-counter-min="1" data-input-counter-max="100" class="form-control text-center" value="1" required>
                                <button type="button" id="increment-button" class="btn btn-outline-secondary">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div id="helper-text-explanation" class="form-text">Input jumlah barang dengan minimal 1 dan maksimal 100.</div>
                    </div>                                                           

                    <div id="barang-container">
                        <hr class="mb-4">
                        <!-- Input Barang Template -->
                        <div class="barang-input-item" data-index="1">
                            <span style="color: black; font-weight: bold; text-align: center;" class="d-block mb-3">Barang 1</span>
                            <div class="mb-3">
                                <label for="serial_number_1" class="form-label">Serial Number</label>
                                <input type="text" id="serial_number_1" name="serial_numbers[]" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="status_barang_1" class="form-label">Kondisi Barang</label>
                                <select id="status_barang_1" name="status_barangs[]" class="form-select select2">
                                    <option selected>Pilih kondisi barang</option>
                                    @foreach ($status_barang as $d)
                                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="kelengkapan_1" class="form-label">Kelengkapan (Opsional)</label>
                                <input type="text" id="kelengkapan_1" name="kelengkapans[]" class="form-control">
                            </div>
                            <button type="button" class="btn btn-danger remove-barang-button d-none">Hapus Barang</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('barang-container');
            const quantityInput = document.getElementById('quantity-input');
            const incrementButton = document.getElementById('increment-button');
            const decrementButton = document.getElementById('decrement-button');

            function createBarangInput(index) {
                const newItem = document.createElement('div');
                newItem.classList.add('barang-input-item');
                newItem.setAttribute('data-index', index);
                newItem.innerHTML = `
                <hr class="mb-4">
                    <span style="color: black; font-weight: bold; text-align: center;" class="d-block mb-3">Barang ${index}</span>
                    <div class="mb-3">
                        <label for="serial_number_${index}" class="form-label">Serial Number</label>
                        <input type="text" id="serial_number_${index}" name="serial_numbers[]" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="status_barang_${index}" class="form-label">Kondisi Barang</label>
                        <select id="status_barang_${index}" name="status_barangs[]" class="form-select select2">
                            <option selected>Pilih kondisi barang</option>
                            @foreach ($status_barang as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kelengkapan_${index}" class="form-label">Kelengkapan (Opsional)</label>
                        <input type="text" id="kelengkapan_${index}" name="kelengkapans[]" class="form-control" />
                    </div>
                    <button type="button" class="btn btn-danger remove-barang-button d-none">Hapus Barang</button>
                `;
                return newItem;
            }

            function updateBarangInputs() {
                const quantity = parseInt(quantityInput.value, 10);
                const items = container.getElementsByClassName('barang-input-item');
                const itemCount = items.length;

                if (quantity > itemCount) {
                    for (let i = itemCount + 1; i <= quantity; i++) {
                        container.appendChild(createBarangInput(i));
                    }
                }

                if (quantity < itemCount) {
                    for (let i = itemCount; i > quantity; i--) {
                        container.removeChild(container.querySelector(`.barang-input-item[data-index="${i}"]`));
                    }
                }
            }

            incrementButton.addEventListener('click', function () {
                if (parseInt(quantityInput.value, 10) < parseInt(quantityInput.dataset.inputCounterMax, 10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) + 1;
                    updateBarangInputs();
                }
            });

            decrementButton.addEventListener('click', function () {
                if (parseInt(quantityInput.value, 10) > parseInt(quantityInput.dataset.inputCounterMin, 10)) {
                    quantityInput.value = parseInt(quantityInput.value, 10) - 1;
                    updateBarangInputs();
                }
            });

            container.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-barang-button')) {
                    const item = event.target.closest('.barang-input-item');
                    container.removeChild(item);
                    Array.from(container.getElementsByClassName('barang-input-item')).forEach((elem, idx) => {
                        elem.setAttribute('data-index', idx + 1);
                        elem.querySelector('span').textContent = `Barang ${idx + 1}`;
                        elem.querySelectorAll('input, select').forEach(input => {
                            const id = input.id.split('_')[0];
                            input.id = `${id}_${idx + 1}`;
                        });
                    });
                }
            });

            $('.select2').select2({
                theme: 'bootstrap-5',
            });

            updateBarangInputs();
        });
    </script>
</body>

</html>
