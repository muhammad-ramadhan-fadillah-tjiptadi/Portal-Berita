@extends('templates.app')

@section('content')
    <style>
        .btn i {
            margin-right: 6px !important;
        }

        .alert-danger-custom {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-danger-custom.show {
            display: block;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .error-modal .modal-content {
            border-left: 4px solid #dc3545;
        }

        .error-modal .modal-header {
            background-color: #f8d7da;
            border-bottom: 1px solid #f5c6cb;
        }
    </style>
    <div class="container mt-5">
        <!-- Custom Alert for Validation Messages -->
        <div id="validationAlert" class="alert-danger-custom">
            <span id="validationMessage"></span>
            <button type="button" class="btn-close float-end" onclick="hideValidationAlert()">&times;</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Kategori</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.export') }}" class="btn btn-alert-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.categories.trash') }}" class="btn btn-alert-warning">
                    <i class="fas fa-trash me-1"></i> Tempat Sampah
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-alert-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Kategori
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Slug</th>
                <th>Jumlah Artikel</th>
                <th>Aksi</th>
            </tr>
            @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '-' }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->posts_count }}</td>
                    <td class="d-flex">
                        <a href="{{ route('admin.categories.edit', ['categorie' => $category->id]) }}"
                            class="btn btn-sm btn-alert-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-alert-danger delete-btn"
                            data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data kategori</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="fas fa-trash text-danger me-2"></i>
                            Konfirmasi Hapus Kategori
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kategori "<strong id="categoryName"></strong>"?</p>
                        <p class="text-muted">Kategori akan dipindahkan ke tempat sampah dan dapat dipulihkan kembali.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showValidationAlert(message) {
            const alert = document.getElementById('validationAlert');
            const messageElement = document.getElementById('validationMessage');
            messageElement.textContent = message;
            alert.classList.add('show');

            // Auto-hide after 5 seconds
            setTimeout(() => {
                hideValidationAlert();
            }, 5000);
        }

        function hideValidationAlert() {
            const alert = document.getElementById('validationAlert');
            alert.classList.remove('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const categoryName = document.getElementById('categoryName');

            deleteButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const categoryId = this.dataset.categoryId;
                    const categoryNameValue = this.dataset.categoryName;

                    try {
                        // Check if category can be deleted
                        const response = await fetch(
                            `/admin/categories/${categoryId}/check-delete`);
                        const data = await response.json();

                        if (data.canDelete) {
                            // Show confirmation modal
                            categoryName.textContent = categoryNameValue;
                            deleteForm.action = `/admin/categories/${categoryId}`;
                            deleteModal.show();
                        } else {
                            // Show Laravel-style alert with validation message
                            showValidationAlert(data.message);
                        }
                    } catch (error) {
                        console.error('Error checking category deletion:', error);
                        // Fallback to direct form submission if AJAX fails
                        categoryName.textContent = categoryNameValue;
                        deleteForm.action = `/admin/categories/${categoryId}`;
                        deleteModal.show();
                    }
                });
            });
        });
    </script>
@endsection
