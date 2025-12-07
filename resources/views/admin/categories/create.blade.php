@extends('templates.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-folder-plus text-primary me-2"></i>
                    Tambah Kategori Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Masukkan nama kategori">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted">Contoh: Teknologi, Kesehatan, Pendidikan, dll.</small>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">
                            Deskripsi
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="3"
                                placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
