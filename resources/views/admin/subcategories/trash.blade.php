@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }

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

        .btn-alert-secondary {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-secondary:hover {
            background-color: #d1d3d6;
            color: #383d41;
        }

        .btn-sm.btn-alert-success,
        .btn-sm.btn-alert-danger,
        .btn-sm.btn-alert-primary,
        .btn-sm.btn-alert-secondary {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Sub Kategori Terhapus</h3>
            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-alert-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Sub kategori yang ada di tempat sampah telah dihapus secara sementara. Anda dapat mengembalikannya atau
            menghapusnya secara permanen.
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Sub Kategori</th>
                <th>Kategori Induk</th>
                <th>Deskripsi</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
            @forelse($subcategories as $index => $subcategory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->category->name ?? '-' }}</td>
                    <td>{{ $subcategory->description ?? '-' }}</td>
                    <td>{{ $subcategory->deleted_at->format('d M Y H:i') }}</td>
                    <td class="d-flex">
                        <form action="{{ route('admin.subcategories.restore', $subcategory->id) }}" method="POST"
                            class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-alert-success">
                                <i class="fas fa-undo"></i> Kembalikan
                            </button>
                        </form>
                        <form action="{{ route('admin.subcategories.force-delete', $subcategory->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-alert-danger">
                                <i class="fas fa-trash"></i>Hapus Permanen
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tempat sampah sub kategori kosong</p>
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-alert-primary">Kembali ke Daftar
                            Sub Kategori</a>
                    </td>
                </tr>
            @endforelse
        </table>

        <div class="mt-3">
            {{ $subcategories->links() }}
        </div>
    </div>
@endsection
