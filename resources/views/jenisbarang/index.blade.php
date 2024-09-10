@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-delete, .icon-detail {
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

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
    }
</style>

<div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
    <h4 class="mt-3" style="color: #8a8a8a;">Data Jenis Barang</h4>
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
        <!-- Add Button -->
        <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
            <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
            Add
        </a>              
    
        <!-- Delete Selected Button -->
        <button id="deleteSelected" class="btn btn-danger d-none" style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
            <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
            Delete Selected
        </button>          
    </div>    
    
    <table class="table table-bordered table-striped table-hover" id="jenisBarangTable" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>No</th>
                <th>Type Item</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 4 integration -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<!-- Script untuk inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#jenisBarangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ config('app.api_url') }}/jenisbarang',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}'
                }
            },
            columns: [
                {
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
                    data: 'id',
                    orderable: false,
                    render: function(data) {
                        return `
                            <div class="d-flex">
                                <a href="/jenisbarang/edit/${data}" class="btn-edit btn-action" aria-label="Edit">
                                    <iconify-icon icon="mdi:edit" class="icon-edit"></iconify-icon>
                                </a>
                                <button data-id="${data}" class="btn-action" aria-label="Delete">
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

        // Handle select-all checkbox
        $(document).on('change', '#select-all', function() {
            const isChecked = $(this).is(':checked');
            $('.select-item').prop('checked', isChecked);
            toggleDeleteButton();
        });

        // Handle individual checkboxes
        $(document).on('change', '.select-item', function() {
            toggleDeleteButton();
        });

        // Function to toggle the delete button based on selection
        function toggleDeleteButton() {
            const selected = $('.select-item:checked').length;
            const deleteButton = $('#deleteSelected');
            if (selected > 0) {
                deleteButton.removeClass('d-none');
            } else {
                deleteButton.addClass('d-none');
            }
        }

        // Handle delete selected button click
        $(document).on('click', '#deleteSelected', function() {
            const selected = [];
            $('.select-item:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length > 0) {
                if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                    fetch('/jenisbarang/delete-selected', {
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
                            location.reload();
                        } else {
                            alert('Gagal menghapus data.');
                        }
                    });
                }
            } else {
                alert('Tidak ada data yang dipilih.');
            }
        });
    });
</script>
@endsection
