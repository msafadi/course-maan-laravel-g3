<x-admin-layout title="Create Post">
    <form action="{{ route('admin.posts.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        
        @include('admin.posts._form')
    </form>
</x-admin-layout>