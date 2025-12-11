@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        .btn-alert-primary:hover {
            background-color: #0056b3;
            color: white;
        }

        .badge-admin {
            background-color: #f8d7da;
            color: #842029;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-user {
            background-color: #cfe2ff;
            color: #084298;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn-alert-secondary {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d3d6d8;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-secondary:hover {
            background-color: #d1d3d6;
            color: #383d41;
        }

        .btn-alert-primary {
            background-color: #cce5ff !important;
            color: #004085 !important;
            border: 1px solid #cce5ff !important;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-primary:hover {
            background-color: #b8daff !important;
            color: #004085 !important;
        }

        .current-info {
            background-color: #f8f9fa;
            padding: 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
    </style>

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Edit User: {{ $user->name }}</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-alert-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-edit me-2"></i>Form Edit User
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}"
                                    placeholder="Masukkan nama lengkap user" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label required">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}"
                                    placeholder="Masukkan alamat email" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label required">Role User</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-lock me-2"></i>Ubah Password (Opsional)
                            </h5>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small>Kosongkan field password jika tidak ingin mengubah password user</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Masukkan password baru minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin
                                    diubah.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Ulangi password baru">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Informasi User Saat Ini:</h6>
                            <div class="current-info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>ID:</strong> {{ $user->id }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Role:</strong>
                                        <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                            {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Dibuat:</strong> {{ $user->created_at->format('d-m-Y H:i') }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Artikel:</strong> {{ $user->posts_count ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-alert-secondary">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-alert-primary">
                                    <i class="fas fa-save me-1"></i> Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (password && password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });

        // Clear confirmation when password is cleared
        document.getElementById('password').addEventListener('input', function() {
            const confirmPassword = document.getElementById('password_confirmation');
            if (!this.value) {
                confirmPassword.value = '';
                confirmPassword.setCustomValidity('');
            }
        });

        // Real-time password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            // Update password strength indicator if needed
            console.log('Password strength:', strength + '/4');
        });
    </script>
@endsection
