@extends('templates.app')

@section('content')
    <style>
        .form-container {
            --primary-color: #0da2e7;
            --primary-dark: #0c8dcc;
            --neutral-50: #f9fafb;
            --neutral-100: #f3f4f6;
            --neutral-200: #e5e7eb;
            --neutral-600: #4b5563;
            --neutral-700: #374151;
            --neutral-900: #111827;
            --danger-color: #ef4444;
            --border-radius: 12px;
        }

        .form-container .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-700);
            margin-bottom: 8px;
        }

        .form-container .form-control,
        .form-container .form-select {
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: var(--neutral-50);
        }

        .form-container .form-control:focus,
        .form-container .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(13, 162, 231, 0.1);
        }

        .form-container .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-container .card-header {
            background-color: var(--neutral-50);
            border-bottom: 1px solid var(--neutral-200);
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .form-container .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }

        .form-container .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .form-container .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .form-container .btn-secondary {
            background-color: var(--neutral-200);
            border-color: var(--neutral-200);
            color: var(--neutral-700);
        }

        .form-container .btn-secondary:hover {
            background-color: var(--neutral-100);
            transform: translateY(-1px);
        }

        .form-container .form-group {
            margin-bottom: 20px;
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
            background-color: #d3d6d8;
            color: #383d41;
        }

        .btn-alert-primary {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-primary:hover {
            background-color: #bacff7;
            color: #084298;
        }
    </style>
    <div class="container py-4">
        <div class="card shadow-sm form-container">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-tag text-primary me-2"></i>
                    Tambah Tag Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tags.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nama Tag <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required placeholder="Masukkan nama tag">
                        @error('name')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                        <small class="text-muted">Contoh: Laravel, JavaScript, PHP, dll.</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Tag
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
