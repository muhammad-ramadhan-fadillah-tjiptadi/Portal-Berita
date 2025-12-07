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

        .form-container .form-control.is-invalid,
        .form-container .form-select.is-invalid {
            border-color: var(--danger-color) !important;
            background-color: #fff;
        }

        .form-container .form-control.is-invalid:focus,
        .form-container .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-container .error-message {
            display: block;
            font-size: 13px;
            color: var(--danger-color);
            margin-top: 6px;
            font-weight: 500;
            display: flex;
            align-items: center;
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
                    <i class="fas fa-edit text-primary me-2"></i>
                    Edit Sub Kategori: <strong>{{ $subcategory->name }}</strong>
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Sub Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $subcategory->name) }}" required
                            placeholder="Masukkan nama sub kategori">
                        @error('name')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="categorie_id" class="form-label">Kategori Induk <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('categorie_id') is-invalid @enderror" id="categorie_id"
                            name="categorie_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categorie_id', $subcategory->categorie_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Masukkan deskripsi sub kategori (opsional)">{{ old('description', $subcategory->description) }}</textarea>
                        @error('description')
                            <span class="error-message">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.subcategories.index') }}" class="btn-alert-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn-alert-primary">
                            <i class="fas fa-save me-1"></i> Update Sub Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
