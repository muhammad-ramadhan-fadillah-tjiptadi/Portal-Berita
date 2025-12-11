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
            <h3>Data Komentar</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.comments.export') }}" class="btn btn-alert-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.comments.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.comments.create') }}" class="btn btn-alert-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Komentar
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Isi Komentar</th>
                <th>Artikel</th>
                <th>User</th>
                <th>Tanggal</th>
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
                    <td>{{ $comment->created_at->format('d-m-Y H:i') }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
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
                    <td colspan="6" class="text-center">Tidak ada data komentar</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
