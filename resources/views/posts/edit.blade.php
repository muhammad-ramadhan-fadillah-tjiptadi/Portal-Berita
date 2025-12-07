@extends('templates.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Artikel</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" onchange="updateSubcategories(this.value)" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="subcategory-container">
                            <label for="subcategory_id" class="form-label">Sub Kategori</label>
                            <select class="form-select" id="subcategory_id" name="subcategory_id">
                                <option value="">Pilih Sub Kategori</option>
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ $post->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Utama (Kosongkan jika tidak ingin mengubah)</label>
                            @if($post->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('user.posts.drafts') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <div class="d-flex gap-2">
                                <button type="submit" name="publish" value="0" class="btn btn-outline-primary">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                                @if($post->status !== 'published')
                                <button type="submit" name="publish" value="1" class="btn btn-primary">
                                    <i class="bi bi-upload me-1"></i> Publikasikan
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateSubcategories(categoryId) {
        if (!categoryId) {
            document.getElementById('subcategory-container').innerHTML = `
                <label for="subcategory_id" class="form-label">Sub Kategori (Opsional)</label>
                <select class="form-select" id="subcategory_id" name="subcategory_id" disabled>
                    <option value="">Pilih Kategori terlebih dahulu</option>
                </select>`;
            return;
        }

        fetch(`/api/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(subcategories => {
                let options = '<option value="">Pilih Sub Kategori</option>';
                subcategories.forEach(subcategory => {
                    options += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                });

                document.getElementById('subcategory-container').innerHTML = `
                    <label for="subcategory_id" class="form-label">Sub Kategori (Opsional)</label>
                    <select class="form-select" id="subcategory_id" name="subcategory_id">
                        ${options}
                    </select>`;
            });
    }
</script>
@endpush
@endsection
