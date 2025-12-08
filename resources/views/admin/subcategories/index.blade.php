@extends('templates.app')

@section('content')
    <style>
        .btn-alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-success:hover {
            background-color: #c2e0d8;
            color: #0f5132;
        }

        .btn-alert-warning {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-warning:hover {
            background-color: #ffe69c;
            color: #664d03;
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

        .btn-alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-danger:hover {
            background-color: #f5c2c7;
            color: #842029;
        }

        .btn-sm.btn-alert-primary,
        .btn-sm.btn-alert-danger {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Sub Kategori</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subcategories.export') }}" class="btn btn-alert-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.subcategories.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-alert-primary">
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
                            class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST"
                            class="d-inline">
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
                    <td colspan="5" class="text-center">Tidak ada data sub kategori</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
