@extends('templates.app')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="fw-bold">Draft Artikel</h1>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Buat Artikel Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($drafts->isEmpty())
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Tidak ada draft artikel yang tersedia.
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($drafts as $draft)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            @if($draft->image)
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $draft->image) }}"
                                        class="w-100 h-100"
                                        style="object-fit: cover;"
                                        alt="{{ $draft->title }}">
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-{{ $draft->status === 'published' ? 'success' : 'warning' }}">
                                            {{ $draft->status === 'published' ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-muted small">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $draft->updated_at->diffForHumans() }}
                                    </div>
                                    <span class="mx-2 text-muted">•</span>
                                    <div class="text-muted small">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $draft->category->name ?? 'Tanpa Kategori' }}
                                    </div>
                                </div>
                                <h2 class="h5 fw-bold mb-3">
                                    {{ $draft->title }}
                                </h2>
                                <p class="text-muted mb-4">
                                    {{ Str::limit(strip_tags($draft->content), 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <div class="btn-group">
                                        <form action="{{ route('posts.publish', $draft) }}" method="POST" class="me-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Publikasikan artikel ini?')">
                                                <i class="fas fa-upload me-1"></i> Publish
                                            </button>
                                        </form>
                                        <form action="#" method="POST" class="ms-1" onsubmit="return confirm('Hapus draft ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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

            @if($drafts->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        {{ $drafts->onEachSide(1)->links() }}
                    </nav>
                </div>
            @endif
        @endif
    </div>

    <style>
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .btn-outline-primary {
            border-radius: 20px;
            padding: 0.375rem 1rem;
        }
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
        }
    </style>
@endsection
