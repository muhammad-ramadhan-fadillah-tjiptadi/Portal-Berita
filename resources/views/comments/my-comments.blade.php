@extends('templates.app')

@section('content')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0">Komentar Saya</h4>
                    </div>
                    <div class="card-body p-0">
                        @if ($comments->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($comments as $comment)
                                    <div class="list-group-item border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <!-- Breadcrumb Navigation -->
                                                <nav aria-label="breadcrumb" class="mb-1">
                                                    <ol class="breadcrumb mb-1"
                                                        style="--bs-breadcrumb-divider: '>'; font-size: 0.8rem;">
                                                        @if ($comment->post && $comment->post->category)
                                                            <li class="breadcrumb-item">
                                                                <a href="{{ route('category.posts', $comment->post->category) }}"
                                                                    class="text-decoration-none">
                                                                    {{ $comment->post->category->name }}
                                                                </a>
                                                            </li>
                                                            @if ($comment->post->subCategory)
                                                                <li class="breadcrumb-item">
                                                                    <a href="{{ route('category.subcategory.posts', ['category' => $comment->post->category, 'subcategory' => $comment->post->subCategory]) }}"
                                                                        class="text-decoration-none">
                                                                        {{ $comment->post->subCategory->name }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <li class="breadcrumb-item active" aria-current="page">
                                                                <a href="{{ $comment->post ? route('posts.show', $comment->post) : '#' }}"
                                                                    class="text-decoration-none fw-medium">
                                                                    {{ $comment->post ? $comment->post->title : 'Postingan tidak tersedia' }}
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li class="text-muted small">Postingan tidak tersedia</li>
                                                        @endif
                                                    </ol>
                                                </nav>
                                            </div>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ Auth::user()->getProfilePhotoUrl() }}"
                                                    alt="{{ Auth::user()->name }}" class="rounded-circle"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ Auth::user()->name }}</strong>
                                                    </div>
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('comments.edit', $comment) }}"
                                                            class="btn btn-sm btn-alert-primary">
                                                            <i class="fas fa-edit" style="color: #084298;"></i>
                                                        </a>
                                                        <form action="{{ route('comments.destroy', $comment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-alert-danger">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <p class="mb-0 mt-2">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="p-3">
                                {{ $comments->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-comments fa-3x text-muted"></i>
                                </div>
                                <h5 class="text-muted">Anda belum memiliki komentar</h5>
                                <p class="text-muted">Kunjungi artikel dan berikan komentar Anda!</p>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-newspaper me-1"></i> Lihat Artikel
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            /* border-left: 4px solid #198754; */
        }

        .list-group-item {
            padding: 1.25rem;
            transition: background-color 0.2s;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .btn-ellipsis {
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .list-group-item:hover .btn-ellipsis {
            opacity: 1;
        }

        .btn-alert-primary {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
            padding: 0.4rem 0.6rem;
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
            color: #842029;
        }
    </style>
@endsection
