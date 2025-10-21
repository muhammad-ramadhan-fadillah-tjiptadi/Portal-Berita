@extends('templates.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Draft Artikel</h2>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Buat Artikel Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($drafts->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Belum ada draft artikel.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Terakhir Diperbarui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drafts as $draft)
                            <tr>
                                <td>
                                    @if($draft->image)
                                        <img src="{{ asset('storage/' . $draft->image) }}" alt="{{ $draft->title }}" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div style="width: 60px; height: 40px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="text-decoration-none">
                                        {{ $draft->title }}
                                    </a>
                                </td>
                                <td>{{ $draft->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $draft->status === 'published' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $draft->status === 'published' ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $draft->updated_at->diffForHumans() }}</td>
                                <td>
                                    <div class="btn-group">
                                        <form action="{{ route('posts.publish', $draft) }}" method="POST" class="me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Publikasikan artikel ini?')">
                                                <i class="fas fa-upload me-1"></i> Publikasikan
                                            </button>
                                        </form>
                                        <a href="#" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="#" method="POST" onsubmit="return confirm('Hapus draft ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $drafts->links() }}
            </div>
        @endif
    </div>
@endsection
