@extends('layouts.navigation')

@section('content')
    @can('roles.view')
        <div class="container shadow-sm mt-3" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Manage Roles and User Roles</h4>
                @can('roles.create')
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
                @endcan
            </div>

            <!-- Tabel Data Roles -->
            <table id="roles-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        @canany(['roles.edit', 'roles.delete'])
                            <th>Action</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <script>
                const permissionNames = @json($permissionNames);

                $(document).ready(function() {
                    $('#roles-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ env('API_URL') }}/roles',
                            headers: {
                                'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                            },
                            dataSrc: 'data'
                        },
                        columns: [{
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'permissions',
                                name: 'permissions',
                                render: function(data, type, row) {
                                    if (!data || data.length === 0) return '';

                                    const displayLimit = 3;
                                    const permissions = data.slice(0, displayLimit).map(permission =>
                                        permissionNames[permission] || permission);
                                    const joinedPermissions = permissions.join(', ');

                                    if (data.length <= displayLimit) {
                                        return joinedPermissions;
                                    }
                                    return `<span title="${data.map(permission => permissionNames[permission] || permission).join(', ')}">
                                ${joinedPermissions}... 
                                <a href="#" onclick="showPermissions('${data.map(permission => permissionNames[permission] || permission).join(', ')}', '${row.name}'); return false;">[Show More]</a>
                                </span>`;
                                }
                            },
                            @canany(['roles.edit', 'roles.delete'])
                                {
                                    data: 'id',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row) {
                                        let actionHtml = '';
                                        @can('roles.edit')
                                            actionHtml +=
                                                `<a href="{{ route('roles.index') }}/${data}/edit" class="btn btn-warning btn-sm">Edit</a> `;
                                        @endcan
                                        @can('roles.delete')
                                            actionHtml +=
                                                `<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(${data})">Delete</button>`;
                                        @endcan
                                        return actionHtml;
                                    }
                                }
                            @endcanany
                        ]
                    });
                });

                function showPermissions(permissionText, roleName) {
                    // Ubah permissionText menjadi array dan gunakan Bootstrap list group untuk tampilannya
                    const permissionsArray = permissionText.split(', ').map(permission =>
                        `<li class="list-group-item" style="color:#3c3c3c !important;font-size:16px">${permission}</li>`);
                    const permissionsHtml = `<ul class="list-group">${permissionsArray.join('')}</ul>`;

                    Swal.fire({
                        title: `Permissions for ${roleName}`,
                        html: `<div style="max-height: 300px; overflow-y: auto;">${permissionsHtml}</div>`,
                        icon: 'info',
                        confirmButtonText: 'Close',
                    });
                }

                function confirmDelete(roleId) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `{{ route('roles.destroy', '') }}/${roleId}`,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Deleted!',
                                        'The role has been deleted.',
                                        'success'
                                    );
                                    $('#roles-table').DataTable().ajax.reload();
                                },
                                error: function(response) {
                                    Swal.fire(
                                        'Error!',
                                        'There was an issue deleting the role.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                }
            </script>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Assign Roles to Users</h4>
            </div>

            <!-- Tabel Assign Roles ke User -->
            <table id="users-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Roles</th>
                        @can('roles.edit')
                            <th>Action</th>
                        @endcan
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <script>
                $(document).ready(function() {
                    $('#users-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ env('API_URL') }}/roles',
                            headers: {
                                'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                            },
                            dataSrc: 'users'
                        },
                        columns: [{
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'roles',
                                name: 'roles',
                                render: function(data) {
                                    return data ? data.join(', ') : '';
                                }
                            },
                            @can('roles.edit')
                                {
                                    data: 'id',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false,
                                    render: function(data, type, row) {
                                        return `<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editRoleModal" data-user-id="${data}"
                                            data-user-name="${row.name}" data-user-roles="${row.roles.join(',')}">
                                            Edit
                                        </button>`;
                                    }
                                }
                            @endcan
                        ]
                    });
                });
            </script>

            <!-- Modal untuk Edit Role -->
            @can('roles.edit')
                <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editRoleModalLabel">Edit Roles</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editRoleForm" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="roles" class="form-label">Select Roles</label>
                                        <select name="roles[]" id="rolesSelect" class="form-select">
                                            <!-- Options akan ditambahkan dengan JavaScript -->
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update Roles</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const editRoleModal = document.getElementById('editRoleModal');

                        // Mengisi dropdown roles dengan data dari API saat modal dibuka
                        editRoleModal.addEventListener('show.bs.modal', function(event) {
                            const button = event.relatedTarget;
                            const userId = button.getAttribute('data-user-id');
                            const userName = button.getAttribute('data-user-name');
                            const userRoles = button.getAttribute('data-user-roles').split(',');

                            // Set action URL pada form sesuai user ID
                            const form = document.getElementById('editRoleForm');
                            form.action = `/roles/assign-role/${userId}`;

                            // Update title modal
                            const modalTitle = editRoleModal.querySelector('.modal-title');
                            modalTitle.textContent = `Edit Roles for ${userName}`;

                            // Ambil data roles dari API untuk mengisi select box
                            $.ajax({
                                url: '{{ env('API_URL') }}/roles',
                                headers: {
                                    'Authorization': 'Bearer ' + '{{ $jwt_token }}'
                                },
                                success: function(response) {
                                    const rolesSelect = document.getElementById('rolesSelect');
                                    rolesSelect.innerHTML = ''; // Kosongkan select box terlebih dahulu

                                    // Isi select box dengan data roles dari API
                                    response.data.forEach(function(role) {
                                        const option = document.createElement('option');
                                        option.value = role.name;
                                        option.textContent = role.name;
                                        option.selected = userRoles.includes(role.name);
                                        rolesSelect.appendChild(option);
                                    });
                                }
                            });
                        });
                    });
                </script>
            @endcan

        </div>
    @endcan
@endsection
