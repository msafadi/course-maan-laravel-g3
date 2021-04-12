<x-admin-layout title="Posts">
    <div class="mb-3">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-outline-primary">Create new</a>
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
                <th>Title</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created At</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td><a href="{{ route('admin.posts.download', [$post->id]) }}"><img src="{{ $post->image_url }}" height="65" alt=""></a></td>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->slug }}</td>
                <td>{{ $post->category_id }}</td>
                <td>{{ $post->status }}</td>
                <td>{{ $post->created_at }}</td>
                <td><a href="{{ route('admin.posts.edit', [$post->id]) }}" class="btn btn-sm btn-outline-success">Edit</a></td>
                <td><form action="{{ route('admin.posts.destroy', [$post->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form></td>
            </tr>
            @empty
            <tr>
                <td colspan="9">No Posts.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</x-admin-layout>

