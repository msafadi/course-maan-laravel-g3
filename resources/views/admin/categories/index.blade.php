<x-admin-layout title="Categories">
    <div class="mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-outline-primary">Create new</a>
    </div>

    @if(session()->has('success'))
    <div class="alert alert-success">
        {{-- session('success') --}}
        {{ session()->get('success') }}
    </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Parent</th>
                <th>Products #</th>
                <th>Created At</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(count($categories) > 0)
            @foreach($categories as $category)
            <tr>
                <td>{{ $loop->index }}</td>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->parent_name }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->created_at }}</td>
                <td><a href="{{ route('admin.categories.edit', [$category->id]) }}" class="btn btn-sm btn-outline-success">Edit</a></td>
                <td><form action="{{ route('admin.categories.destroy', [$category->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="8">No categories.</td>
            </tr>
            @endif
        </tbody>
    </table>
    {{ $categories->links() }}
</x-admin-layout>

