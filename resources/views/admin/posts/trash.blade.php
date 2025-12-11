@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }

        .badge-admin {
            background-color: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-user {
            background-color: #6c757d;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-warning {
            background-color: #d4edda;
            color: #155724;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-info {
            background-color: #cce5ff;
            color: #004085;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn-alert-success {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
            border: 1px solid #d1ecf1 !important;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-success:hover {
            background-color: #bee5eb !important;
            color: #0c5460 !important;
        }
    </style>

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Tempat Sampah Artikel</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-alert-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Artikel yang ada di tempat sampah telah dihapus secara sementara. Anda dapat mengembalikannya atau menghapusnya
            secara permanen.
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Dihapus Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $index => $post)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ Str::limit($post->title, 50) }}</strong>
                            <br>
                            <small class="text-muted">{{ $post->slug }}</small>
                        </td>
                        <td>
                            @if ($post->user)
                                {{ $post->user->name }}
                            @else
                                <span class="text-muted">Unknown</span>
                            @endif
                        </td>
                        <td>
                            @if ($post->category)
                                <span class="badge badge-info">{{ $post->category->name }}</span>
                            @else
                                <span class="text-muted">No Category</span>
                            @endif
                        </td>
                        <td>
                            @if ($post->status == 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>{{ $post->deleted_at->format('d-m-Y H:i') }}</td>
                        <td class="d-flex">
                            <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST"
                                class="d-inline me-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-alert-success">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('admin.posts.force-delete', $post->id) }}" method="POST"
                                class="d-inline">
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
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tempat sampah kosong</p>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-alert-primary">Kembali ke Daftar
                                Artikel</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($posts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
