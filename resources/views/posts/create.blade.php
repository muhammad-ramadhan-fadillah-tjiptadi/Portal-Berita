@extends('templates.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Artikel Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Artikel</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Utama (Opsional)</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Artikel</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Publikasi</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="publish" id="publishNow" value="1" checked>
                                <label class="form-check-label" for="publishNow">
                                    <i class="fas fa-globe-americas me-1"></i> Publikasikan Sekarang
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="publish" id="saveAsDraft" value="0">
                                <label class="form-check-label" for="saveAsDraft">
                                    <i class="fas fa-save me-1"></i> Simpan sebagai Draft
                                </label>
                            </div>
                            <div class="form-text">Pilih "Simpan sebagai Draft" jika ingin menyimpan artikel tanpa mempublikasikannya sekarang.</div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Artikel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
