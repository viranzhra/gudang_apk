@extends('layouts.navigation')

@section('content')
    <style>
        .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .icon-edit,
        .icon-delete,
        .icon-detail {
            color: #ffffff;
            font-size: 18px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 5px;
        }

        .icon-detail {
            background-color: #112337;
        }

        .icon-edit {
            background-color: #01578d;
        }

        .icon-delete {
            background-color: #910a0a;
        }

        .btn-action:hover .icon-edit,
        .btn-action:hover .icon-delete {
            opacity: 0.8;
        }

        #notification {
            position: fixed;
            top: 10px;
            right: 10px;
            width: 300px;
            padding: 15px;
            border-radius: 5px;
            z-index: 9999;
            display: none;
            text-align: center;
            justify-content: flex-start;
            /* Tetap di sebelah kiri */
            align-items: center;
            text-align: left;
            /* Teks tetap rata kiri */
            /* Hidden by default */
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            height: 80px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            height: 80px;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            height: 80px;
        }
    </style>

    <div class="container mt-3 rounded-4 shadow-sm" style="padding-bottom: 15px; padding-top: 10px; min-width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>

        <div class="d-flex align-items-center justify-content-between pb-3 pt-2">
            <div class="d-flex align-items-center">
                <h4>Customer Management</h4>
            </div>

            <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
                <!-- Add Button -->
                <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
                    <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                    Add
                </a>
                <!-- Delete Selected Button -->
                <button id="deleteSelected" class="btn btn-danger d-none"
                    style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                    <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                    Delete Selected
                </button>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="customer-table" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th class="d-flex justify-content-center align-items-center">No</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahDataModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Customer</label>
                            <input type="text" id="nama" name="nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" value="customer123" class="form-control" disabled />
                                <style>.btn-outline-secondary{background-color:var(--bs-secondary-bg);--bs-btn-hover-bg:none;--bs-btn-active-bg:none;border:var(--bs-border-width) solid #e0e6eb;--bs-btn-hover-border-color:#e0e6eb;--bs-btn-active-border-color:#e0e6eb;-webkit-box-shadow:inset 0 1px 2px rgba(0, 0, 0, 0.075);box-shadow:inset 0 1px 2px rgba(0, 0, 0, 0.075)}</style>
                                <button class="btn btn-outline-secondary" style="" type="button" id="togglePassword">
                                    <iconify-icon icon="mdi:eye" id="eyeIcon" style="color: black;"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        <script>
                            document.getElementById('togglePassword').addEventListener('click', function() {
                                const passwordInput = document.getElementById('password');
                                const eyeIcon = document.getElementById('eyeIcon');

                                if (passwordInput.type === 'password') {
                                    passwordInput.type = 'text';
                                    eyeIcon.setAttribute('icon', 'mdi:eye-off');
                                } else {
                                    passwordInput.type = 'password';
                                    eyeIcon.setAttribute('icon', 'mdi:eye');
                                }
                            });
                        </script>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Address</label>
                            <input type="text" id="alamat" name="alamat" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Phone</label>
                            <input type="text" inputmode="numeric" id="telepon" name="telepon" class="form-control"
                                required />
                            <small id="teleponHelp" class="form-text text-muted">Phone number cannot contain spaces,
                                parentheses, or plus signs.</small>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Description</label>
                            <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit HTML -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCustomerForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit-address" name="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-phone" class="form-label">Phone</label>
                            <input type="text" inputmode="numeric" id="edit-phone" name="telepon"
                                class="form-control" required />
                            <small id="teleponHelp" class="form-text text-muted">Phone number cannot contain spaces,
                                parentheses, or plus signs.</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-description" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: black">
                    Are you sure you want to delete <span id="customerName"></span>?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn"
                            style="background-color: #910a0a; color: white;">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Data -->
    <div class="modal fade" id="detailData" tabindex="-1" aria-labelledby="detailDataLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailDataLabel">Detail Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-3"><strong>Customer:</strong></div>
                        <div class="col-8"><span class="detail-nama"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><strong>Email:</strong></div>
                        <div class="col-8"><span class="detail-email"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><strong>Address:</strong></div>
                        <div class="col-8"><span class="detail-alamat"></span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><strong>Phone:</strong></div>
                        <div class="col-8"><span class="detail-telepon"></span></div>
                    </div>
                    <div class="row">
                        <div class="col-3"><strong>Description:</strong></div>
                        <div class="col-8"><span class="detail-keterangan"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan dipilih -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong><span id="selectedCount"></span> selected data?</p><strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #910a0a; color: white;"
                        id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    {{-- detail --}}
    <script>
        $(document).on('click', '.btn-detail', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // mengambil data customer menggunakan API
            $.ajax({
                url: `{{ config('app.api_url') }}/customers/${id}`,
                type: 'GET',
                success: function(data) {
                    // sesuaikan dengan yang di modal
                    $('.detail-nama').text(data.nama);
                    $('.detail-email').text(data.email);
                    $('.detail-alamat').text(data.alamat);
                    $('.detail-telepon').text(data.telepon);
                    $('.detail-keterangan').text(data.keterangan);

                    // perbarui judul dan menampilkan modal
                    $('#detailDataLabel').text('Detail Customer');
                    $('#detailData').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch customer data:', xhr.responseText);
                    // alert('Failed to fetch customer data.');
                }
            });
        });
    </script>

    {{-- validasi input telepon --}}
    <script>
        document.getElementById('telepon').addEventListener('input', function() {
            // menghapus spasi, tanda kurung, dan tanda plus pada input form telepon
            this.value = this.value.replace(/[()\+\s]/g, '');
        });

        // document.getElementById('edit-phone').addEventListener('input', function() {
        //     // menghapus spasi, tanda kurung, dan tanda plus pada input form telepon
        //     this.value = this.value.replace(/[()\+\s]/g, '');
        // });

        const phoneInput = document.getElementById('edit-phone');
        const teleponHelp = document.getElementById('teleponHelp');

        // Menghapus karakter yang tidak diinginkan saat mengklik input
        phoneInput.addEventListener('focus', function() {
            this.value = this.value.replace(/[()\+\s]/g, ''); // Hapus karakter yang tidak diinginkan
            teleponHelp.style.color = ''; // Kembalikan warna teks ke default saat fokus
        });

        // // Memeriksa dan mengubah warna teks ketika input berubah
        // phoneInput.addEventListener('input', function() {
        //     // Hapus karakter yang tidak diinginkan dari input
        //     this.value = this.value.replace(/[()\+\s]/g, '');

        //     // Memeriksa apakah input mengandung karakter yang tidak diinginkan
        //     if (/[()\+\s]/.test(this.value)) {
        //         teleponHelp.style.color = 'red'; // Ubah warna menjadi merah jika ada karakter yang tidak diinginkan
        //     } else {
        //         teleponHelp.style.color = ''; // Kembalikan warna default jika tidak ada karakter yang tidak diinginkan
        //     }
        // });
    </script>

    {{-- konfirmasi hapus --}}
    <script>
        $(document).ready(function() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let deleteUrl;
            let customerId; // Menyimpan customerId di luar scope

            $(document).on('click', '.btn-delete', function() {
                customerId = $(this).data('id'); // Simpan customerId
                const customerName = $(this).closest('tr').find('td:nth-child(3)').text();

                // Tampilkan nama pelanggan di modal
                $('#customerName').text(customerName);

                // Tentukan URL untuk delete API
                deleteUrl = `{{ config('app.api_url') }}/customers/${customerId}`;

                // Update action form
                $('#deleteForm').attr('action', deleteUrl);

                // Tampilkan modal
                deleteModal.show();
            });

            // Event listener untuk tombol konfirmasi hapus
            $('#deleteForm').on('submit', function(event) {
                event.preventDefault(); // Mencegah pengiriman form default

                console.log('Mengirim permintaan DELETE ke:', $(this).attr('action')); // Debug URL

                // Kirim request delete ke API menggunakan jQuery
                // $.ajax({
                //     url: $(this).attr('action'),
                //     method: 'DELETE',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'Authorization': 'Bearer ' + '{{ session('token') }}' // Pastikan token valid
                //     },
                //     success: function(data) {
                //         console.log('Respons dari server:', data); // Debug respons
                //         if (data.success) {
                //             // Jika berhasil, tutup modal dan hapus baris dari tabel
                //             deleteModal.hide();
                //             $(`[data-id="${customerId}"]`).closest('tr').remove();
                //             alert('Customer berhasil dihapus!');
                //         } else {
                //             // Tampilkan pesan error jika gagal
                //             alert('Gagal menghapus customer.');
                //         }
                //     },
                //     error: function(jqXHR, textStatus, errorThrown) {
                //         console.error('Error:', textStatus, errorThrown);
                //         alert('Terjadi kesalahan saat menghapus customer.');
                //     }
                // });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: `{{ config('app.api_url') }}/customers/${id}`,
                    type: 'GET',
                    success: function(data) {
                        $('#edit-id').val(data.id);
                        $('#edit-name').val(data.nama);
                        $('#edit-email').val(data.email);
                        $('#edit-address').val(data.alamat);
                        $('#edit-phone').val(data.telepon);
                        $('#edit-description').val(data.keterangan);
                        $('#editDataModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch customer data:', xhr.responseText);
                        alert('Failed to fetch customer data.');
                    }
                });
            });

            // untuk edit data
            $('#editCustomerForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#edit-id').val() - 1;
                var formData = $(this).serialize();
                $.ajax({
                    url: `{{ config('app.api_url') }}/customers/${id}`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#editDataModal').modal('hide');
                        $('#customer-table').DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to update customer data:', xhr.responseText);
                        alert('Failed to update customer data.');
                    }
                });
            });
        });
    </script>

    <!-- Script untuk inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#customer-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ config('app.api_url') }}/customers',
                    headers: {
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    }
                },
                columns: [{
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="select-item" value="${data}">`;
                        }
                    },
                    {
                        data: null,
                        sortable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama',
                        name: 'customer.nama',
                    },
                    {
                        data: 'email',
                        name: 'users.email',
                    },
                    {
                        data: 'alamat',
                        name: 'customer.alamat',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                const truncatedText = data.length > 30 ? data.substring(0, 30) +
                                    '...' : data; // Batasi menjadi 30 karakter
                                return truncatedText;
                            }
                            return data; // Kembalikan data asli untuk tipe lainnya
                        }
                    },
                    {
                        data: 'telepon',
                        name: 'customer.telepon',
                    },
                    {
                        data: 'keterangan',
                        name: 'customer.keterangan',
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
                            <div class="d-flex">
                                <button title="Detail" aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                    <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                </button>
                                <button title="Edit" data-id="${data}" class="btn-edit btn-action" aria-label="Edit">
                                    <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                                </button>
                                <button title="Delete" data-id="${data}" class="btn-delete btn-action" aria-label="Delete">
                                    <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                                </button>
                            </div>
                        `;
                        }
                    }
                ],
                order: [
                    [2, 'asc'] // Mengurutkan berdasarkan kolom 'nama'
                ]
            });

            // select-all checkbox
            $(document).on('change', '#select-all', function() {
                const isChecked = $(this).is(':checked');
                $('.select-item').prop('checked', isChecked);
                toggleDeleteButton();
            });

            // individual checkboxes
            $(document).on('change', '.select-item', function() {
                toggleDeleteButton();
            });

            // Fungsi untuk mengaktifkan atau menonaktifkan tombol hapus berdasarkan item yang dipilih
            function toggleDeleteButton() {
                const selected = $('.select-item:checked').length;
                const deleteButton = $('#deleteSelected');
                if (selected > 0) {
                    deleteButton.removeClass('d-none');
                } else {
                    deleteButton.addClass('d-none');
                }
            }
        });
    </script>


    <!-- Script untuk menampilkan notifikasi -->
    <script>
        $(document).ready(function() {
            // Function untuk menampilkan notifikasi
            function showNotification(type, message) {
                let notificationTitle = '';
                let notificationClass = '';

                //  Mengatur judul dan kelas berdasarkan tipe notifikasi
                switch (type) {
                    case 'success':
                        notificationTitle = 'Success!';
                        notificationClass = 'alert-success';
                        break;
                    case 'error':
                        notificationTitle = 'Error!';
                        notificationClass = 'alert-danger';
                        break;
                    default:
                        notificationTitle = 'Notification';
                        notificationClass = 'alert-info';
                }

                // mengatur konten notifikasi
                $('#notificationTitle').text(notificationTitle);
                $('#notificationMessage').text(message);
                $('#notification').removeClass('alert-success alert-danger alert-info').addClass(notificationClass);

                // menampilkan notifikasi
                $('#notification').fadeIn();

                // menyembunyikan notifikasi setelah 3 detik
                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }

            $(document).on('click', '#deleteSelected', function() {
                const selected = [];
                $('.select-item:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    // Update jumlah data yang dipilih di modal
                    $('#selectedCount').text(selected.length);

                    // Tampilkan modal konfirmasi
                    $('#confirmDeleteModal').modal('show');

                    // Simpan daftar ID yang akan dihapus
                    $('#confirmDeleteButton').data('ids', selected);
                } else {
                    showNotification('error', 'No data selected.');
                }
            });

            // Konfirmasi penghapusan data terpilih
            $(document).on('click', '#confirmDeleteButton', function() {
                const selected = $(this).data('ids');

                // Lakukan request untuk menghapus data terpilih
                fetch('{{ config('app.api_url') }}/customers/delete-selected', {
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
                        // Set status penghapusan berhasil ke sessionStorage
                        sessionStorage.setItem('deleteStatus', 'success');

                        // Reload halaman
                        location.reload();
                    } else {
                        showNotification('error', 'Failed to delete selected data.');
                    }
                    $('#confirmDeleteModal').modal('hide');
                }).catch(() => {
                    showNotification('error', 'An error occurred while deleting data.');
                    $('#confirmDeleteModal').modal('hide');
                });
            });

            // Cek status penghapusan setelah halaman di-reload
            $(window).on('load', function() {
                if (sessionStorage.getItem('deleteStatus') === 'success') {
                    showNotification('success', 'Selected data was successfully deleted!');

                    // Hapus status penghapusan setelah menampilkan notifikasi
                    sessionStorage.removeItem('deleteStatus');
                }
            });

            // Handle form submission for adding data
            // $('form[action="{{ route('customer.store') }}"]').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = $(this).serialize();
            //     $.post($(this).attr('action'), formData, function(response) {
            //         console.log('Response:', response);  // Debugging line
            //         if (response.success) {
            //             showNotification('success', 'Customer berhasil ditambahkan!');
            //             $('#tambahDataModal').modal('hide');
            //             $('#customer-table').DataTable().ajax.reload();
            //         } else {
            //             showNotification('error', 'Gagal menambahkan customer.');
            //         }
            //     }).fail(function(jqXHR, textStatus, errorThrown) {
            //         console.error('Error:', textStatus, errorThrown);  // Debugging line
            //         showNotification('error', 'Terjadi kesalahan saat menambahkan customer.');
            //     });
            // });

            // untuk notifikasi form tambah data
            $('form[action="{{ route('customer.store') }}"]').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize(); // Mengambil data dari form
                let $submitButton = $(this).find(
                    'button[type="submit"]'); // Menyimpan referensi tombol submit

                // Disable tombol dan tampilkan loading
                $submitButton.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i>');

                // Mengirimkan data melalui AJAX
                $.post($(this).attr('action'), formData, function(response) {
                    if (response.success) {
                        showNotification('success', response.message); // Menampilkan pesan sukses
                        $('#tambahDataModal').modal('hide'); // Menutup modal
                        $('#customer-table').DataTable().ajax.reload(); // Reload tabel

                        // Reset form setelah berhasil submit
                        $('form[action="{{ route('customer.store') }}"]')[0].reset();
                    } else {
                        showNotification('error', response.message); // Menampilkan pesan error
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', jqXHR.responseText);
                    showNotification('error',
                        'An error occurred while adding customer data.'); // Menampilkan pesan error
                }).always(function() {
                    // Enable kembali tombol submit dan kembalikan teks aslinya
                    $submitButton.prop('disabled', false).html('Save');
                });
            });

            // Reset form ketika modal ditutup
            $('#tambahDataModal').on('hidden.bs.modal', function() {
                $('form[action="{{ route('customer.store') }}"]')[0].reset();
            });

            // $('form[action="{{ route('customer.store') }}"]').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = $(this).serialize();
            //     $.post($(this).attr('action'), formData, function(response) {
            //         if (response.success) {
            //             showNotification('success', response.message); // menampilkan pesan sukses
            //             $('#tambahDataModal').modal('hide');
            //             $('#customer-table').DataTable().ajax.reload();
            //         } else {
            //             showNotification('error', response.message); // menampilan pesan error
            //         }
            //     }).fail(function(jqXHR, textStatus, errorThrown) {
            //         console.error('Error:', jqXHR.responseText); 
            //         showNotification('error', 'Terjadi kesalahan saat menambahkan customer.');
            //     });
            // });

            // untuk notifikasi edit data
            $('#editCustomerForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let id = $('#edit-id').val() - 1;
                $.ajax({
                    url: `{{ config('app.api_url') }}/customers/${id}`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showNotification('success', 'Customer data successfully updated!');
                            $('#editDataModal').modal('hide');
                            $('#customer-table').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Failed to update customer data.');
                        }
                    },
                    error: function() {
                        showNotification('error',
                            'An error occurred while updating customer data.');
                    }
                });
            });

            // untuk notifikasi delete
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + '{{ session('token') }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            showNotification('success', 'Customer data successfully deleted!');
                            $('#deleteModal').modal('hide');
                            $('#customer-table').DataTable().ajax.reload();
                        } else {
                            showNotification('error', 'Failed to delete customer data.');
                        }
                    },
                    error: function() {
                        showNotification('error',
                            'An error occurred while deleting customer data.');
                    }
                });
            });
        });
    </script>
@endsection
