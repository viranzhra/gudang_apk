@extends('layouts.navigation')

@section('content')
    @can('roles.create')
        <div class="container shadow-sm mt-3" style="border-radius: 20px;">
            <h2>Create New Role</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('roles.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="permissions" class="form-label">Permissions</label>

                    @foreach ($groupedPermissions as $module => $permissions)
                        <div class="card mb-2" style="padding-left: 0 !important;">
                            <div class="card-header">
                                <h5 class="mb-0">Modul {{ ucfirst($module) }}</h5>
                            </div>
                            <div id="{{ $module }}Permissions" class="collapse show">
                                <div class="card-body">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission['name'] }}"
                                                class="form-check-input" id="permission-{{ $permission['id'] }}">
                                            <label class="form-check-label" for="permission-{{ $permission['id'] }}">
                                                {{ $permissionNames[$permission['name']] ?? ucfirst(str_replace("{$module}.", '', $permission['name'])) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <button type="submit" class="btn btn-primary">Create Role</button>
            </form>
        </div>
    @endcan
@endsection
