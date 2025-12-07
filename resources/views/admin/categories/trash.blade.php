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

        .btn-sm.btn-alert-success,
        .btn-sm.btn-alert-danger,
        .btn-sm.btn-alert-primary {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Sampah Kategori</h3>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-alert-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Dihapus Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trashedCategories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td>{{ $category->deleted_at->format('d-m-Y H:i') }}</td>
                        <td class="d-flex">
                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST"
                                class="me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-alert-success">
                                    <i class="fas fa-undo me-1"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-alert-danger">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data di tempat sampah</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
