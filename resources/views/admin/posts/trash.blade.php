<x-admin-layout title="Deleted Posts">
    <div class="mb-3">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-outline-primary">Create new</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Title</th>
                <th>Slug</th>
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
                <td>{{ $post->status }}</td>
                <td>{{ $post->created_at }}</td>
                <td><form action="{{ route('admin.posts.restore', [$post->id]) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                </form></td>
                <td><form action="{{ route('admin.posts.force-delete', [$post->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete for ever</button>
                </form></td>
            </tr>
            @empty
            <tr>
                <td colspan="9">No Posts.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $posts->links() }}
</x-admin-layout>

