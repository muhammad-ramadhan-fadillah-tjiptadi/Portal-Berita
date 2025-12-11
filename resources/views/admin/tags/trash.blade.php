@extends('templates.app')

@section('content')
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
                <th>Slug</th>
                <th>Dihapus pada</th>
                <th>Aksi</th>
            </tr>
            @forelse($tags as $index => $tag)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->slug }}</td>
                    <td>{{ $tag->deleted_at->format('d-m-Y H:i') }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.tags.restore', $tag->id) }}" class="btn btn-sm btn-alert-success me-2"
                            onclick="return confirm('Apakah Anda yakin ingin mengembalikan tag ini?')">
                            <i class="fas fa-undo"></i> Kembalikan
                        </a>
                        <form action="{{ route('admin.tags.force-delete', $tag->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-alert-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus tag ini secara permanen?')">
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
