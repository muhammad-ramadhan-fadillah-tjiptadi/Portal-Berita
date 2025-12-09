@extends('templates.app')

@section('content')

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Kategori</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.export') }}" class="btn btn-alert-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.categories.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-alert-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Slug</th>
                <th>Jumlah Artikel</th>
                <th>Aksi</th>
            </tr>
            @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '-' }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->posts_count }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.categories.edit', ['categorie' => $category->id]) }}"
                            class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-alert-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kategori</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
