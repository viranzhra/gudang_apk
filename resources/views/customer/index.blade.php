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

    <div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
        <!-- Notification Element -->
        <div id="notification" class="alert" style="display: none;">
            <strong id="notificationTitle">Notification</strong>
            <p id="notificationMessage"></p>
        </div>

        <h4 class="mt-3" style="color: #8a8a8a;">Customer Management</h4>
        {{-- <div id="notification" class="alert alert-dismissible fade" role="alert" style="display: none;">
            <strong id="notificationTitle"></strong> <span id="notificationMessage"></span>
            <button type="button" class="btn-close" aria-label="Close"></button>
        </div> --}}
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="customer-table" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>No</th>
                        <th>Customer</th>
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
                            <input type="text" id="keterangan" name="keterangan" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
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
                    <p>Are you sure you want to delete the selected data?</p>
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
                    $('.detail-alamat').text(data.alamat);
                    $('.detail-telepon').text(data.telepon);
                    $('.detail-keterangan').text(data.keterangan);

                    // perbarui judul dan menampilkan modal
                    $('#detailDataLabel').text('Detail Customer');
                    $('#detailData').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch customer data:', xhr.responseText);
                    alert('Failed to fetch customer data.');
                }
            });
        });
    </script>

    <script>
        document.getElementById('telepon').addEventListener('input', function() {
            // menghapus spasi, tanda kurung, dan tanda plus pada input form telepon
            this.value = this.value.replace(/[()\+\s]/g, '');
        });

        document.getElementById('edit-phone').addEventListener('input', function() {
            // menghapus spasi, tanda kurung, dan tanda plus pada input form telepon
            this.value = this.value.replace(/[()\+\s]/g, '');
        });
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
                var id = $('#edit-id').val();
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
            // inisialisasi DataTable
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
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
                                <div class="d-flex">
                                    <button aria-label="Detail" data-id="${data}" class="btn-detail btn-action" style="border: none;">
                                        <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                                    </button>
                                    <button data-id="${data}" class="btn-edit btn-action" aria-label="Edit">
                                        <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                                    </button>
                                    <button id="deleteButton-${data}" data-id="${data}" class="btn-action btn-delete" aria-label="Delete">
                                        <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                                    </button>
                                </div>
                        `;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
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
                    // Tampilkan modal konfirmasi
                    $('#confirmDeleteModal').modal('show');
                    // Simpan daftar ID yang akan dihapus
                    $('#confirmDeleteButton').data('ids', selected);
                } else {
                    showNotification('error', 'Tidak ada data yang dipilih.');
                }
            });

            // konfirmasi button hapus yang terpilih
            $(document).on('click', '#confirmDeleteButton', function() {
                const selected = $(this).data('ids');

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
                        showNotification('success', 'Selected data was successfully delected!');
                        $('#customer-table').DataTable().ajax.reload(); // Reload DataTable
                    } else {
                        showNotification('error', 'Gagal menghapus data.');
                    }
                    $('#confirmDeleteModal').modal('hide');
                    // Sembunyikan tombol "Hapus Terpilih"
                    $('#deleteSelected').hide();
                }).catch(() => {
                    showNotification('error', 'Terjadi kesalahan saat menghapus data.');
                    $('#confirmDeleteModal').modal('hide');
                });
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
                    'Terjadi kesalahan saat menambahkan customer.'); // Menampilkan pesan error
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
                let id = $('#edit-id').val();
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
                            showNotification('error', 'Gagal memperbarui customer.');
                        }
                    },
                    error: function() {
                        showNotification('error',
                            'Terjadi kesalahan saat memperbarui customer.');
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
                            showNotification('error', 'Gagal menghapus customer.');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan saat menghapus customer.');
                    }
                });
            });
        });
    </script>
@endsection
