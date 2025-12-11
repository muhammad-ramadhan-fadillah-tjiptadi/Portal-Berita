@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }
    </style>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Tempat Sampah Tag</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tags.index') }}" class="btn btn-alert-primary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Data Tag
                </a>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Tag yang ada di tempat sampah telah dihapus secara sementara. Anda dapat mengembalikannya atau menghapusnya
            secara permanen.
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Tag</th>
                <th>Jumlah Artikel Yang Memakai Tag</th>
                <th>Dihapus pada</th>
                <th>Aksi</th>
            </tr>
            @forelse($tags as $index => $tag)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->posts_count ?? 0 }}</td>
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
                    <td colspan="5" class="text-center">Tidak ada data tag di tempat sampah</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
