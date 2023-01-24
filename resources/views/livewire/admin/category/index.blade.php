<div>

    @include('livewire.admin.category.modal-form')

    <div class="row">
        <div class="col-md-12">

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3>
                        Categories List
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryModal"
                            class="btn btn-primary btn-sm float-end">Add Category</a>
                    </h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = ($categories->currentPage() - 1) * $categories->perPage();
                            @endphp
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->status == '1' ? 'Hidden' : 'Visible' }}</td>
                                    <td>
                                        <a href="#" wire:click="editCategory({{ $category->id }})"
                                            data-bs-toggle="modal" data-bs-target="#updateCategoryModal"
                                            class="btn btn-success">Edit</a>
                                        <a href="#" wire:click="deleteCategory({{ $category->id }})"
                                            data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                            class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        window.addEventListener('close-modal', event => {
            $('#addCategoryModal').modal('hide');
            $('#updateCategoryModal').modal('hide');
            $('#deleteCategoryModal').modal('hide');
        });
    </script>
@endpush
