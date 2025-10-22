@extends('templates.app')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // Function to create slug from text
        function createSlug(text) {
            return text.toString().toLowerCase()
                .replace(/[^\w\-]+/g, '-')  // Replace non-word chars with -
                .replace(/\-\-+/g, '-')      // Replace multiple - with single -
                .replace(/^-+/, '')           // Trim - from start of text
                .replace(/-+$/, '');          // Trim - from end of text
        }

        // Auto-generate slug when name changes
        nameInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.value === '') {
                slugInput.value = createSlug(this.value);
            }
        });

        // Allow manual editing of slug, but keep it URL-friendly
        slugInput.addEventListener('input', function() {
            this.value = createSlug(this.value);
        });
    });
</script>
@endpush

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
                        <label for="slug" class="form-label">
                            Slug <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('slug') is-invalid @enderror"
                            id="slug"
                            name="slug"
                            value="{{ old('slug') }}"
                            required
                            placeholder="Slug akan otomatis terisi">
                        @error('slug')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        {{-- <small class="text-muted">URL-friendly version of the name</small> --}}
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
