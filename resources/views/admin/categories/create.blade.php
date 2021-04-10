<x-admin-layout title="Create Category">
    <form action="{{ route('admin.categories.store') }}" method="post">
        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
        {{-- csrf_field() --}}
        @csrf
        
        @include('admin.categories._form')
    </form>
</x-admin-layout>