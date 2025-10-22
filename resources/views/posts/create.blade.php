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
@if(!request()->has('category_id'))
                        <form action="{{ route('posts.create') }}" method="GET">
                            @csrf
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Pilih Kategori Terlebih Dahulu</label>
                                <select class="form-select" id="category_id" name="category_id" onchange="this.form.submit()" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        @else
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Artikel</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" disabled>
                                    <option>{{ $categories->where('id', request('category_id'))->first()->name ?? 'Pilih Kategori' }}</option>
                                </select>
                                <div class="mt-2">
                                    <a href="{{ route('posts.create') }}" class="text-decoration-none d-inline-flex align-items-center text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-right me-1" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5zm14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5z"/>
                                        </svg>
                                        <span class="fw-medium">Ganti Kategori</span>
                                    </a>
                                </div>
                            </div>

                            @if(isset($subcategories) && $subcategories->count() > 0)
                            <div class="mb-3">
                                <label for="subcategory_id" class="form-label">Sub Kategori (Opsional)</label>
                                <select class="form-select" id="subcategory_id" name="subcategory_id">
                                    <option value="">Pilih Sub Kategori</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Utama (Opsional)</label>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Artikel</label>
                                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="publish" value="0" class="btn btn-outline-secondary">
                                        <i class="bi bi-file-earmark-text me-1"></i> Simpan Draft
                                    </button>
                                    <button type="submit" name="publish" value="1" class="btn btn-primary">
                                        <i class="bi bi-upload me-1"></i> Publish
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
