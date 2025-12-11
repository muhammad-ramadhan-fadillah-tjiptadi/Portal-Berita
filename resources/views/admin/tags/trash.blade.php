@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }

        .btn-alert-secondary {
            background-color: #e2e3e5;
            color: #383d41;
            border: 1px solid #d3d6d8;
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
    </style>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Tempat Sampah Tag</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tags.index') }}" class="btn btn-alert-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Tag yang ada di tempat sampah telah dihapus secara sementara. Anda dapat mengembalikannya atau menghapusnya
            secara permanen.
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tag</th>
                    <th>Dihapus pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $index => $tag)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->deleted_at->format('d-m-Y H:i') }}</td>
                        <td class="d-flex">
                            <form action="{{ route('admin.tags.restore', $tag->id) }}" method="POST" class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-alert-success">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('admin.tags.force-delete', $tag->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-alert-danger">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tempat sampah tag kosong</p>
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-alert-primary">Kembali ke Daftar
                                Tag</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
