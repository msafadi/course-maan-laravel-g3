<x-admin-layout title="Edit Post">
    <form action="{{ route('admin.posts.update', [$post->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
       
        @include('admin.posts._form')
    </form>
</x-admin-layout>