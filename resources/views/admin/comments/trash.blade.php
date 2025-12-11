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
            <h3>Tempat Sampah Komentar</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.comments.index') }}" class="btn btn-alert-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Komentar yang ada di tempat sampah telah dihapus secara sementara. Anda dapat mengembalikannya atau menghapusnya
            secara permanen.
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Isi Komentar</th>
                <th>Artikel</th>
                <th>User</th>
                <th>Dihapus pada</th>
                <th>Aksi</th>
            </tr>
            @forelse($comments as $index => $comment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div style="max-width: 300px; word-wrap: break-word;">
                            {{ Str::limit($comment->content, 100) }}
                            @if (strlen($comment->content) > 100)
                                <small class="text-muted">...</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if ($comment->post)
                            <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank">
                                {{ Str::limit($comment->post->title, 30) }}
                            </a>
                        @else
                            <span class="text-muted">Artikel dihapus</span>
                        @endif
                    </td>
                    <td>
                        @if ($comment->user)
                            {{ $comment->user->name }}
                        @else
                            <span class="text-muted">User dihapus</span>
                        @endif
                    </td>
                    <td>{{ $comment->deleted_at->format('d-m-Y H:i') }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.comments.restore', $comment) }}" class="btn btn-sm btn-alert-success me-2">
                            <i class="fas fa-undo"></i> Kembalikan
                        </a>
                        <form action="{{ route('admin.comments.force-delete', $comment) }}" method="POST" class="d-inline">
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
                    <td colspan="6" class="text-center">Tidak ada data komentar di tempat sampah</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
