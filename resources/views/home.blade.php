@extends('templates.app')

@section('content')
    <div class="container py-5">
        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {!! Session::get('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (Session::get('logout'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('logout') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @isset($category)
            <h1 class="mb-5 fw-bold text-center">Berita {{ $category->name }}</h1>
        @else
            <h1 class="mb-5 fw-bold text-center">Berita Terkini</h1>
        @endisset

        @if (isset($category) && $category->subCategories->isNotEmpty())
            <!-- Modern Subcategory Navigation -->
            <div class="subcategory-nav mb-5">
                <div class="subcategory-container">
                    <ul class="subcategory-list">
                        <li class="subcategory-item">
                            <a href="{{ route('category.posts', $category) }}"
                                class="subcategory-link {{ !isset($subcategory) ? 'active' : '' }}">
                                <span>Semua {{ $category->name }}</span>
                            </a>
                        </li>
                        @foreach ($category->subCategories as $subCat)
                            <li class="subcategory-item">
                                <a href="{{ route('category.subcategory.posts', ['category' => $category, 'subcategory' => $subCat->id]) }}"
                                    class="subcategory-link {{ isset($subcategory) && $subcategory->id == $subCat->id ? 'active' : '' }}">
                                    {{ $subCat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <style>
            /* Subcategory Navigation Styles */
            .subcategory-nav {
                position: relative;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding: 0.5rem 0;
                margin: 0 -15px;
            }

            .subcategory-container {
                max-width: 100%;
                padding: 0 15px;
            }

            .subcategory-list {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                gap: 0.75rem;
            }

            .subcategory-item {
                flex: 0 0 auto;
            }

            .subcategory-link {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1.25rem;
                border-radius: 50px;
                background: #f8f9fa;
                color: #495057;
                text-decoration: none;
                font-weight: 500;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                border: 1px solid #e9ecef;
                white-space: nowrap;
            }

            .subcategory-link:hover {
                background: #e9ecef;
                color: #0da2e7;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .subcategory-link.active {
                background: #0da2e7;
                color: white;
                border-color: #0da2e7;
                box-shadow: 0 4px 12px rgba(13, 162, 231, 0.2);
            }


            @media (max-width: 768px) {
                .subcategory-nav {
                    padding: 0.5rem 0;
                }

                .subcategory-link {
                    padding: 0.4rem 1rem;
                    font-size: 0.85rem;
                }
            }
        </style>

        <div class="row g-4">
            @forelse($posts as $post)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm overflow-hidden">
                        @if ($post->image)
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                <img src="{{ asset('storage/' . $post->image) }}" class="w-100 h-100"
                                    style="object-fit: cover; transition: transform 0.3s ease;" alt="{{ $post->title }}"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-primary bg-opacity-75">{{ $post->category->name }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="far fa-user me-2"></i>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <span class="mx-2 text-muted">•</span>
                                <div class="text-muted small">
                                    <i class="far fa-clock me-1"></i>
                                    <time datetime="{{ $post->created_at->toIso8601String() }}">
                                        {{ $post->created_at->diffForHumans() }}
                                    </time>
                                </div>
                            </div>
                            <h2 class="h4 fw-bold mb-3">
                                <a href="#" class="text-decoration-none text-dark hover-text-primary">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-muted mb-4">
                                {{ Str::limit(strip_tags($post->content), 150) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary w-100">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Tidak ada draft artikel yang tersedia.
                    </div>
                </div>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    {{ $posts->onEachSide(1)->links() }}
                </nav>
            </div>
        @endif
    </div>

    <style>
        .hover-text-primary:hover {
            color: #0d6efd !important;
            transition: color 0.2s ease;
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

        .btn-outline-primary {
            border-width: 2px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
@endsection
