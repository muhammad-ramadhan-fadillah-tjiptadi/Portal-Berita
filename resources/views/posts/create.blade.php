@extends('templates.app')

@section('content')
    <style>
        :root {
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

        .btn-alert-primary {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-alert-primary:hover {
            background-color: #c3e6cb;
            color: #155724;
        }

        .btn-alert-secondary {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-alert-secondary:hover {
            background-color: #d6d8db;
            color: #383d41;
        }

        .btn-alert-success {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-alert-success:hover {
            background-color: #bacff7;
            color: #084298;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-700);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: var(--neutral-50);
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(13, 162, 231, 0.1);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger-color) !important;
            background-color: #fff;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            display: block;
            font-size: 13px;
            color: var(--danger-color);
            margin-top: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Tambah Artikel Baru</h4>
                    </div>
                    <div class="card-body">
                        @if (!request()->has('category_id'))
                            <form action="{{ route('user.posts.create') }}" method="GET">
                                @csrf
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Pilih Kategori Terlebih Dahulu</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                        name="category_id" required onchange="this.form.submit()">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </form>
                        @else
                            <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data"
                                novalidate>
                                @csrf
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">

                                <div class="form-group">
                                    <label for="title" class="form-label">Judul Artikel <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-select" disabled>
                                        <option>
                                            {{ $categories->where('id', request('category_id'))->first()->name ?? 'Pilih Kategori' }}
                                        </option>
                                    </select>
                                    <div class="mt-2">
                                        <a href="{{ route('user.posts.create') }}"
                                            class="text-decoration-none d-inline-flex align-items-center text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-arrow-left-right me-1" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z" />
                                            </svg>
                                            <span class="fw-medium">Ganti Kategori</span>
                                        </a>
                                    </div>
                                </div>

                                @if (isset($subcategories) && $subcategories->count() > 0)
                                    <div class="form-group">
                                        <label for="subcategory_id" class="form-label">Sub Kategori <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('subcategory_id') is-invalid @enderror"
                                            id="subcategory_id" name="subcategory_id" required>
                                            <option value="">Pilih Sub Kategori</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}"
                                                    {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subcategory_id')
                                            <span class="error-message">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="image" class="form-label">Gambar Utama <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file"
                                        id="image" name="image" accept="image/*" required>
                                    @error('image')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content" class="form-label">Isi Artikel <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10"
                                        required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tags" class="form-label">Tags <span class="text-muted small">(Pisahkan
                                            dengan koma)</span></label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                        id="tags" name="tags" value="{{ old('tags') }}">
                                    @error('tags')
                                        <span class="error-message">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('home') }}" class="btn-alert-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="submit" name="status" value="draft"
                                            class="btn btn-alert-primary me-2">
                                            <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                                        </button>
                                        <button type="submit" name="status" value="published"
                                            class="btn btn-alert-success">
                                            <i class="fas fa-upload me-1"></i> Publish
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
