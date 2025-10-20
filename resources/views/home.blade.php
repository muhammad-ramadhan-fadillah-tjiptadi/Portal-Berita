@extends('templates.app')

@section('content')
    <div class="container mt-4">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }} <b>Selamat Datang, {{ Auth::user()->name }}</b></div>
        @endif
        @if (Session::get('logout'))
            <div class="alert alert-success">{{ Session::get('logout') }}</div>
        @endif

        @isset($category)
            <h2 class="mb-4">Berita {{ $category->name }}</h2>
        @else
            <h2 class="mb-4">Berita Terkini</h2>
        @endisset

        <div class="row">
            @forelse($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text text-muted">
                                <small>{{ $post->category->name }} • {{ $post->created_at->diffForHumans() }}</small>
                            </p>
                            <p class="card-text">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                            <a href="#" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Tidak ada berita yang tersedia.</div>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
