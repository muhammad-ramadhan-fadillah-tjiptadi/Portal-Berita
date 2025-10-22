@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Sub Kategori</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subcategories.export') }}" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.subcategories.trash') }}" class="btn btn-warning text-white">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Sub Kategori
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Sub Kategori</th>
                <th>Kategori Induk</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
            @forelse($subcategories as $index => $subcategory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->category->name ?? '-' }}</td>
                    <td>{{ $subcategory->description ?? '-' }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}"
                            class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data sub kategori</td>
                </tr>
            @endforelse
        </table>

        <div class="mt-3">
            {{ $subcategories->links() }}
        </div>
    </div>
@endsection
