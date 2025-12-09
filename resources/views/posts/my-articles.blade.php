@extends('templates.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="fw-bold">Artikel Saya</h1>
            <a href="{{ route('user.posts.create') }}" class="btn-alert-primary">
                <i class="fas fa-plus me-1"></i> Buat Artikel Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($articles->isEmpty())
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Belum ada artikel yang dipublikasikan.
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($articles as $article)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            @if ($article->image)
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $article->image) }}" class="w-100 h-100"
                                        style="object-fit: cover;" alt="{{ $article->title }}">
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-success">
                                            Published
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i>
                                        {{ ($article->published_at ?? $article->created_at)->diffForHumans() }}
                                    </div>
                                    <span class="mx-2 text-muted">•</span>
                                    <div class="text-muted small">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $article->category->name ?? 'Tanpa Kategori' }}
                                    </div>
                                </div>
                                <h2 class="h5 fw-bold mb-3">
                                    {{ $article->title }}
                                </h2>
                                <p class="text-muted mb-4">
                                    {{ Str::limit(strip_tags($article->content), 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('posts.show', $article) }}" class="btn btn-sm btn-alert-primary"
                                        target="_blank">
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                    <div class="d-flex">
                                        <a href="{{ route('user.posts.edit', $article) }}"
                                            class="btn btn-sm btn-alert-primary me-2">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('user.posts.destroy', $article) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-alert-danger d-flex align-items-center justify-content-center"
                                                style="width: 36px; height: 32px;"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($articles->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        {{ $articles->onEachSide(1)->links() }}
                    </nav>
                </div>
            @endif
        @endif
    </div>

    <style>
        .btn-alert-primary {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }

        .btn-alert-primary:hover {
            background-color: #bacff7;
            color: #084298;
        }

        .btn-alert-danger {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 0.4rem 0.6rem;
        }

        .btn-alert-danger:hover {
            background-color: #f5c2c7;
            color: #842029;
        }

        .btn-alert-danger i {
            font-size: 1rem;
        }

        .btn-alert-primary,
        .btn-alert-danger {
            text-decoration: none;
            padding: 0.25rem 0.5rem;
            display: inline-flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
        }
    </style>
@endsection
