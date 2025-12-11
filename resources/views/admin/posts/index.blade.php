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

        .btn-alert-danger {
            background-color: #f8d7da !important;
            color: #842029 !important;
            border: 1px solid #f8d7da !important;
            text-decoration: none;
            padding: 0.375rem 0.75rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-alert-danger:hover {
            background-color: #f1aeb5 !important;
            color: #842029 !important;
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
            <h3>Data Artikel</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.posts.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-alert-primary">
                    <i class="fas fa-plus"></i> Tambah Artikel
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
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
                    <td class="d-flex">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-alert-danger"
                                onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada artikel</p>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-alert-primary">Tambah Artikel
                            Pertama</a>
                    </td>
                </tr>
            @endforelse
        </table>

        @if ($posts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
