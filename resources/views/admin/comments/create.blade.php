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

        .form-container .btn-primary:hover {
            background-color: #bacff7;
            color: #084298;
        }

        .form-container .btn-secondary {
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

        .form-container .btn-secondary:hover {
            background-color: #d3d6d8;
            color: #383d41;
        }

        .form-container .form-group {
            margin-bottom: 20px;
        }
    </style>
    <div class="container py-4">
        <div class="card shadow-sm form-container">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-comments text-primary me-2"></i>
                    Tambah Komentar Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.comments.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="post_id" class="form-label">
                            Artikel <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('post_id') is-invalid @enderror" id="post_id" name="post_id"
                            required>
                            <option value="">Pilih Artikel</option>
                            @foreach ($posts as $post)
                                <option value="{{ $post->id }}" {{ old('post_id') == $post->id ? 'selected' : '' }}>
                                    {{ $post->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('post_id')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="user_id" class="form-label">
                            User <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id"
                            required>
                            <option value="">Pilih User</option>
                            @foreach (\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content" class="form-label">
                            Isi Komentar <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5"
                            required placeholder="Masukkan isi komentar">{{ old('content') }}</textarea>
                        @error('content')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                        <small class="text-muted">Maksimal 1000 karakter</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Komentar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
