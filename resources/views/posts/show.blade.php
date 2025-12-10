@extends('templates.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <article class="mb-5">
                    @if ($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded mb-4"
                            alt="{{ $post->title }}">
                    @endif

                    <h1 class="mb-3 fw-bold">{{ $post->title }}</h1>

                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center me-3">
                            <i class="far fa-user me-2 text-primary"></i>
                            <span>{{ $post->user->name }}</span>
                        </div>
                        <div class="d-flex align-items-center me-3">
                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                            <span>{{ $post->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="far fa-folder me-2 text-primary"></i>
                            <a href="{{ route('category.posts', $post->category) }}" class="text-decoration-none">
                                {{ $post->category->name }}
                            </a>
                            @if ($post->subCategory)
                                <span class="mx-2">/</span>
                                <a href="{{ route('category.subcategory.posts', ['category' => $post->category, 'subcategory' => $post->subCategory]) }}"
                                    class="text-decoration-none">
                                    {{ $post->subCategory->name }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="post-content mb-5">
                        {!! $post->content !!}
                    </div>
                </article>

                <!-- Comments Section -->
                <div class="comments-section mt-5">
                    <h4 class="mb-4 fw-bold">Komentar</h4>

                    @auth
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3"
                                            placeholder="Tulis komentar Anda di sini..." required></textarea>
                                        @error('content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}" class="fw-bold">Login</a> atau
                            <a href="{{ route('signup') }}" class="fw-bold">Daftar</a> untuk memberikan komentar.
                        </div>
                    @endauth

                    <div class="comments-list">
                        @forelse($post->comments as $comment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $comment->user->getProfilePhotoUrl() }}"
                                                 alt="{{ $comment->user->name }}"
                                                 class="rounded-circle me-2"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $comment->user->name }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if (auth()->check() && auth()->id() === $comment->user_id)
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm p-0"
                                                    style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background-color: #e7f1ff; border: 1px solid #b3d1ff;"
                                                    title="Edit komentar">
                                                    <i class="fas fa-edit" style="color: #0d6efd;"></i>
                                                </a>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-alert-danger p-0"
                                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;"
                                                        title="Hapus komentar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted text-center py-4">
                                Belum ada komentar. Jadilah yang pertama berkomentar!
                            </div>
                        @endforelse
                    </div>
                </div>

                @if ($relatedPosts->isNotEmpty())
                    <div class="mb-5">
                        <h3 class="h4 mb-4 fw-bold">Berita Terkait</h3>
                        <div class="row g-4">
                            @foreach ($relatedPosts as $relatedPost)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        @if ($relatedPost->image)
                                            <img src="{{ asset('storage/' . $relatedPost->image) }}" class="card-img-top"
                                                alt="{{ $relatedPost->title }}" style="height: 180px; object-fit: cover;">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('posts.show', $relatedPost) }}"
                                                    class="text-decoration-none text-dark hover-text-primary">
                                                    {{ Str::limit($relatedPost->title, 50) }}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted small">
                                                {{ Str::limit(strip_tags($relatedPost->content), 100) }}
                                            </p>
                                            <a href="{{ route('posts.show', $relatedPost) }}"
                                                class="btn btn-sm btn-outline-primary">Baca Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Berita Terbaru</h5>
                        @foreach (\App\Models\Post::where('status', 'published')->latest()->take(5)->get() as $recentPost)
                            <div class="d-flex mb-3">
                                @if ($recentPost->image)
                                    <img src="{{ asset('storage/' . $recentPost->image) }}" class="rounded me-3"
                                        width="80" height="60" style="object-fit: cover;"
                                        alt="{{ $recentPost->title }}">
                                @endif
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('posts.show', $recentPost) }}"
                                            class="text-decoration-none text-dark hover-text-primary">
                                            {{ Str::limit($recentPost->title, 40) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $recentPost->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .post-content {
            line-height: 1.8;
            color: #4a4a4a;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .post-content p {
            margin-bottom: 1.5rem;
        }

        .post-content h2,
        .post-content h3,
        .post-content h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hover-text-primary:hover {
            color: #0d6efd !important;
        }
    </style>
@endsection

<!-- Edit functionality has been removed -->
