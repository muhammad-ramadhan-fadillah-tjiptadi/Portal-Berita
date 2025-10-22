@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Sub Kategori Terhapus</h3>
            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Sub Kategori</th>
                <th>Kategori Induk</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
            @forelse($subcategories as $index => $subcategory)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->category->name ?? '-' }}</td>
                    <td>{{ $subcategory->deleted_at->format('d M Y H:i') }}</td>
                    <td class="d-flex">
                        <form action="{{ route('admin.subcategories.restore', $subcategory->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-undo"></i> Pulihkan
                            </button>
                        </form>
                        <form action="{{ route('admin.subcategories.force-delete', $subcategory->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Hapus Permanen
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data di tempat sampah</td>
                </tr>
            @endforelse
        </table>

        <div class="mt-3">
            {{ $subcategories->links() }}
        </div>
    </div>
@endsection
