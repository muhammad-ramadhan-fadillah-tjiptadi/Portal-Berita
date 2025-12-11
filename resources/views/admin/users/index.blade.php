@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data User</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-alert-primary">
                    <i class="fas fa-plus me-1"></i> Tambah User
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Jumlah Artikel</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                            {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                        </span>
                    </td>
                    <td>{{ $user->posts_count ?? 0 }}</td>
                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-alert-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data user</td>
                </tr>
            @endforelse
        </table>
    </div>
@endsection
