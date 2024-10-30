@extends('layouts.navigation')

@section('content')
    <div class="container shadow-sm mt-3" style="border-radius: 20px;">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Manage Roles and User Roles</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Add Role</a>
        </div>

        <!-- Tabel Data Roles dengan opsi Edit dan Hapus -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role['name'] }}</td>
                        <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            @foreach ($role['permissions'] as $permission)
                                <!-- Tampilkan nama deskriptif dari permission -->
                                {{ $permissionNames[$permission] ?? $permission }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <!-- Tombol Edit Role hanya jika user memiliki permission edit -->
                            {{-- @can('edit') --}}
                            <a href="{{ route('roles.edit', $role['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                            {{-- @endcan --}}

                            <!-- Tombol Hapus Role hanya jika user memiliki permission destroy -->
                            {{-- @can('destroy') --}}
                            <form action="{{ route('roles.destroy', $role['id']) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                            </form>
                            {{-- @endcan --}}
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
        <!-- Form untuk Menambahkan Role Baru, hanya jika user memiliki permission create -->
        {{-- @can('create')
            <form action="{{ route('roles.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="permissions" class="form-label">Permissions</label>
                    <div class="form-check">
                        @foreach ($permissions as $permission)
                            <input type="checkbox" name="permissions[]" value="{{ $permission['name'] }}" class="form-check-input" id="permission-{{ $permission['id'] }}">
                            <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                {{ $permissionNames[$permission['name']] ?? $permission['name'] }}
                            </label><br>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Role</button>
            </form>
        @endcan --}}

        <hr class="my-4">

        <!-- Form untuk Mengatur Role pada User -->
        <h3>Assign Roles to Users</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ implode(', ', $user['roles']) }}</td>
                        <td>
                            <!-- Tombol Edit Role dengan data-user -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editRoleModal" data-user-id="{{ $user['id'] }}"
                                data-user-name="{{ $user['name'] }}" data-user-roles="{{ implode(',', $user['roles']) }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal untuk Edit Role -->
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
                                    @foreach ($roles as $role)
                                        <option value="{{ $role['name'] }}">{{ $role['name'] }}</option>
                                    @endforeach
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
                editRoleModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget; // Tombol yang diklik untuk membuka modal
                    const userId = button.getAttribute('data-user-id');
                    const userName = button.getAttribute('data-user-name');
                    const userRoles = button.getAttribute('data-user-roles').split(',');

                    // Set action URL pada form sesuai user ID
                    const form = document.getElementById('editRoleForm');
                    form.action = `/roles/assign-role/${userId}`;

                    // Update title modal
                    const modalTitle = editRoleModal.querySelector('.modal-title');
                    modalTitle.textContent = `Edit Roles for ${userName}`;

                    // Update selected options di select box
                    const rolesSelect = document.getElementById('rolesSelect');
                    Array.from(rolesSelect.options).forEach(option => {
                        option.selected = userRoles.includes(option.value);
                    });
                });
            });
        </script>

    </div>
@endsection
