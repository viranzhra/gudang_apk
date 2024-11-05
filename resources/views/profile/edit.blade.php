@extends('layouts.navigation')

@section('content')
<div class="container mt-5">
    <h2>Edit Profil</h2>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user['name'] }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user['email'] }}" required>
        </div>
        <hr>
        <h5>Ubah Password</h5>
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Profil</button>
    </form>
</div>
@endsection
