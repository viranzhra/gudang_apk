@extends('layouts.navigation')

@section('content')

{{-- <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.tailwindcss.com"></script> --}}

<style>
    /* CSS untuk mengatur warna placeholder menjadi abu-abu */
    .form-control::placeholder {
        color: grey;
        opacity: 1; /* pastikan opacity 1 agar warna abu-abu tampil penuh */
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

    .input-group {
        position: relative;
    }
    .form-control {
        padding-left: 30px; /* Adjust padding to create space for the icon */
    }
    .bi {
        position: absolute;
        padding-left: 8px;
        top: 50%;
        left: 10px; /* Adjust position to the left side of the input */
        transform: translateY(-50%);
        font-size: 1rem;
        opacity: 0.7; /* Optional: make the icon a little faded */
    }

    .custom-hr {
        margin-top: 50px;  /* Atur jarak atas */
        margin-bottom: 20px;  /* Atur jarak bawah */
    }
</style>

<div class="container mt-3" style="max-width: 700px; padding: 30px;">
    <h4 class="text-center mb-4" style="color: #8a8a8a;">Edit Profil</h4>
    
    {{-- <!-- Notification Messages -->
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
    @endif --}}

    <!-- Notification Element -->
    <div id="notification" class="alert" style="display: none;">
        <strong id="notificationTitle">Notification</strong>
        <p id="notificationMessage">Not data changed.</p>
    </div>

    <script>
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
                        notificationTitle = 'Info';
                        notificationClass = 'alert-warning';
                }

                // mengatur konten notifikasi
                $('#notificationTitle').text(notificationTitle);
                $('#notificationMessage').text(message);
                $('#notification').removeClass('alert-success alert-danger alert-warning').addClass(notificationClass);

                // menampilkan notifikasi
                $('#notification').fadeIn();

                // menyembunyikan notifikasi setelah 3 detik
                setTimeout(function() {
                    $('#notification').fadeOut();
                }, 3000);
            }

            // Kondisi untuk menampilkan notifikasi jika ada session success atau error
            $(document).ready(function() {
                @if (session('success'))
                    showNotification('success', '{{ session('success') }}');
                @endif

                @if ($errors->any())
                    let errorMessage = '';
                    @foreach ($errors->all() as $error)
                        errorMessage += '{{ is_array($error) ? implode(", ", $error) : $error }}' + '\n';
                    @endforeach
                    showNotification('error', errorMessage.trim());
                @endif
            });
    </script>

    <!-- Profile Form -->
<form method="POST" action="{{ route('profile.update') }}" id="profileForm">
    @csrf
    @method('PUT')

    <!-- Profile Photo Section -->
    <div class="text-center mb-4" style="margin-top: 30px;">
        <div class="d-inline-block position-relative">
            <!-- Conditional Display: Profile Photo or Default Icon -->
            {{-- @if ($user->profile_photo_url) --}}
                {{-- <img src="#" class="rounded-circle shadow-sm border" width="100" height="100" alt="Profile Photo"> --}}
            {{-- @else --}}
                <!-- Default Icon if no profile photo -->
                <iconify-icon icon="line-md:person" class="bg-primary-subtle shadow-sm" style="color: rgb(70, 70, 70); font-size: 50px; border: 1px dashed #635bff; padding: 15px; border-radius: 50%;"></iconify-icon>
            {{-- @endif --}}
        </div>
        
        <div class="mt-3 text-center">
            <label for="profile_photo" class="btn btn-primary fw-bold">
                Change photo
            </label>
            <input type="file" class="form-control d-none" id="profile_photo" name="profile_photo" accept="image/*">
        </div>
    </div>

    <!-- Nama Field -->
    {{-- <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-person"></i>
            </span>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user['name'] }}" required>
        </div>
    </div> --}}

    <!-- Name Field with Icon Inside -->
    <div class="mb-3">
        <label style="color: #7e7a7a;" for="name" class="form-label">Name</label>
        <div class="input-group">
            <input type="text" style="border-radius: 6px;" class="form-control ps-5" name="name" value="{{ $user['name'] }}" required />
            <i class="bi bi-person position-absolute top-50 start-0 translate-middle-y ms-2"></i>
        </div>
    </div>

    <!-- Email Field with Icon Inside -->
    <div class="mb-3">
        <labe style="color: #7e7a7a;"l for="email" class="form-label">Email</labe>
        <div class="input-group">
            <input type="text" style="border-radius: 6px;" class="form-control ps-5 border-none focus:ring-0" name="email" value="{{ $user['email'] }}" required />
            <i class="bi bi-envelope position-absolute top-50 start-0 translate-middle-y ms-2"></i>
        </div>
    </div>

    <!-- Email Field -->
    {{-- <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-envelope"></i>
            </span>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user['email'] }}" required>
        </div>
    </div> --}}

    <!-- Password Section -->
    <hr class="custom-hr">
    <h5>Change Password</h5>
    <p class="text-muted">Leave blank if you don't want to change the password.</p>

    <!-- Password Field -->
    <div class="mb-3" style="margin-top: 20px;">
        <label style="color: #7e7a7a;" for="password" class="form-label">New Password</label>
        <div class="input-group">
            <input type="password" class="form-control ps-5" id="password" name="password" placeholder="New password" />
            <svg style="padding-left: 8px;" class="position-absolute top-50 start-0 translate-middle-y ms-2" xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="#636363" viewBox="0 0 256 256"><path d="M216.57,39.43A80,80,0,0,0,83.91,120.78L28.69,176A15.86,15.86,0,0,0,24,187.31V216a16,16,0,0,0,16,16H72a8,8,0,0,0,8-8V208H96a8,8,0,0,0,8-8V184h16a8,8,0,0,0,5.66-2.34l9.56-9.57A79.73,79.73,0,0,0,160,176h.1A80,80,0,0,0,216.57,39.43ZM224,98.1c-1.09,34.09-29.75,61.86-63.89,61.9H160a63.7,63.7,0,0,1-23.65-4.51,8,8,0,0,0-8.84,1.68L116.69,168H96a8,8,0,0,0-8,8v16H72a8,8,0,0,0-8,8v16H40V187.31l58.83-58.82a8,8,0,0,0,1.68-8.84A63.72,63.72,0,0,1,96,95.92c0-34.14,27.81-62.8,61.9-63.89A64,64,0,0,1,224,98.1ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path></svg>
            {{-- <svg style="padding-left: 8px;" class="position-absolute top-50 start-0 translate-middle-y ms-2" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#525252" viewBox="0 0 256 256"><path d="M232,98.36C230.73,136.92,198.67,168,160.09,168a71.68,71.68,0,0,1-26.92-5.17h0L120,176H96v24H72v24H40a8,8,0,0,1-8-8V187.31a8,8,0,0,1,2.34-5.65l58.83-58.83h0A71.68,71.68,0,0,1,88,95.91c0-38.58,31.08-70.64,69.64-71.87A72,72,0,0,1,232,98.36Z" opacity="0.2"></path><path d="M216.57,39.43A80,80,0,0,0,83.91,120.78L28.69,176A15.86,15.86,0,0,0,24,187.31V216a16,16,0,0,0,16,16H72a8,8,0,0,0,8-8V208H96a8,8,0,0,0,8-8V184h16a8,8,0,0,0,5.66-2.34l9.56-9.57A79.73,79.73,0,0,0,160,176h.1A80,80,0,0,0,216.57,39.43ZM224,98.1c-1.09,34.09-29.75,61.86-63.89,61.9H160a63.7,63.7,0,0,1-23.65-4.51,8,8,0,0,0-8.84,1.68L116.69,168H96a8,8,0,0,0-8,8v16H72a8,8,0,0,0-8,8v16H40V187.31l58.83-58.82a8,8,0,0,0,1.68-8.84A63.72,63.72,0,0,1,96,95.92c0-34.14,27.81-62.8,61.9-63.89A64,64,0,0,1,224,98.1ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path></svg> --}}
        <div id="passwordError" class="text-danger mt-1" style="display: none;"></div>
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-3" style="margin-top: 15px;">
        <label style="color: #7e7a7a;" for="password_confirmation" class="form-label">Confirm New Password</label>
        <div class="input-group">
            <input type="password" class="form-control ps-5 border-none focus:ring-0" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" />
            <svg style="padding-left: 8px;" class="position-absolute top-50 start-0 translate-middle-y ms-2" xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="#636363" viewBox="0 0 256 256"><path d="M216.57,39.43A80,80,0,0,0,83.91,120.78L28.69,176A15.86,15.86,0,0,0,24,187.31V216a16,16,0,0,0,16,16H72a8,8,0,0,0,8-8V208H96a8,8,0,0,0,8-8V184h16a8,8,0,0,0,5.66-2.34l9.56-9.57A79.73,79.73,0,0,0,160,176h.1A80,80,0,0,0,216.57,39.43ZM224,98.1c-1.09,34.09-29.75,61.86-63.89,61.9H160a63.7,63.7,0,0,1-23.65-4.51,8,8,0,0,0-8.84,1.68L116.69,168H96a8,8,0,0,0-8,8v16H72a8,8,0,0,0-8,8v16H40V187.31l58.83-58.82a8,8,0,0,0,1.68-8.84A63.72,63.72,0,0,1,96,95.92c0-34.14,27.81-62.8,61.9-63.89A64,64,0,0,1,224,98.1ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path></svg>
            {{-- <svg style="padding-left: 8px;" class="position-absolute top-50 start-0 translate-middle-y ms-2" xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#525252" viewBox="0 0 256 256"><path d="M232,98.36C230.73,136.92,198.67,168,160.09,168a71.68,71.68,0,0,1-26.92-5.17h0L120,176H96v24H72v24H40a8,8,0,0,1-8-8V187.31a8,8,0,0,1,2.34-5.65l58.83-58.83h0A71.68,71.68,0,0,1,88,95.91c0-38.58,31.08-70.64,69.64-71.87A72,72,0,0,1,232,98.36Z" opacity="0.2"></path><path d="M216.57,39.43A80,80,0,0,0,83.91,120.78L28.69,176A15.86,15.86,0,0,0,24,187.31V216a16,16,0,0,0,16,16H72a8,8,0,0,0,8-8V208H96a8,8,0,0,0,8-8V184h16a8,8,0,0,0,5.66-2.34l9.56-9.57A79.73,79.73,0,0,0,160,176h.1A80,80,0,0,0,216.57,39.43ZM224,98.1c-1.09,34.09-29.75,61.86-63.89,61.9H160a63.7,63.7,0,0,1-23.65-4.51,8,8,0,0,0-8.84,1.68L116.69,168H96a8,8,0,0,0-8,8v16H72a8,8,0,0,0-8,8v16H40V187.31l58.83-58.82a8,8,0,0,0,1.68-8.84A63.72,63.72,0,0,1,96,95.92c0-34.14,27.81-62.8,61.9-63.89A64,64,0,0,1,224,98.1ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path></svg> --}}
        <div id="passwordConfirmationError" class="text-danger mt-1" style="display: none;"></div>
    </div>

    <!-- Submit Button with loading spinner -->
    <button type="submit" class="btn btn-primary mt-3" id="submitButton">
        Edit Profil
        <span id="loadingIcon" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
    </button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('profileForm');
        const submitButton = document.getElementById('submitButton');
        const loadingIcon = document.getElementById('loadingIcon');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const notification = document.getElementById('notification');
        const originalName = nameInput.value;
        const originalEmail = emailInput.value;
        const originalPassword = passwordInput.value;
        const originalConfirmPassword = confirmPasswordInput.value;

        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);

        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Password length check
            if (password.length < 6 && password.length > 0) {
                document.getElementById('passwordError').textContent = 'Password must be at least 6 characters.';
                document.getElementById('passwordError').style.display = 'block';
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }

            // Confirm password match check
            if (confirmPassword && password !== confirmPassword) {
                document.getElementById('passwordConfirmationError').textContent = 'Passwords do not match.';
                document.getElementById('passwordConfirmationError').style.display = 'block';
            } else {
                document.getElementById('passwordConfirmationError').style.display = 'none';
            }
        }

        // Show loading spinner and disable button if data is changed
        form.addEventListener('submit', function (e) {
            const name = nameInput.value;
            const email = emailInput.value;
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Check if any data has changed
            if (
                name === originalName &&
                email === originalEmail &&
                password === originalPassword &&
                confirmPassword === originalConfirmPassword
            ) {
                e.preventDefault(); // Prevent form submission
                showNotification('No data changed.');
            } else {
                // Show loading spinner, hide button text and disable the submit button
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'; // Only show spinner
                loadingIcon.style.display = 'inline-block'; // Show the spinner
            }
        });

        // function showNotification(message) {
        //     // Show notification with message
        //     notification.textContent = message;
        //     notification.style.display = 'block';
        //     setTimeout(() => {
        //         notification.style.display = 'none';
        //     }, 3000); // Hide notification after 3 seconds
        // }
    });
</script>
</div>

<!-- Include Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

@endsection
