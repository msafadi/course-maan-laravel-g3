@if ($errors->any())
<div class="alert alert-danger">
    <ul>
    @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
    @endforeach
    </ul>
</div>
@endif

<div class="form-group">
    <label for="">Title</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}">
    @error('title')
    <p class="invalid-feedback">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="">Category</label>
    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value="">No Category</option>
        @foreach($categories as $category)
        <option value="{{ $category->id }}" @if($category->id == old('category_id', $post->category_id)) selected @endif>{{ $category->name }}</option>
        @endforeach
    </select>
    @error('category_id')
    <p class="invalid-feedback">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="">Content</label>
    <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
    @error('content')
    <p class="invalid-feedback">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="">Image</label>
    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
    @error('image')
    <p class="invalid-feedback">{{ $message }}</p>
    @enderror
</div>
<div class="form-group">
    <label for="">Tags</label>
    <div>
        @foreach($tags as $tag)
        <label for="" class="d-inline-block mx-2">
            <input type="checkbox" name="tag[]" value="{{ $tag->id }}" @if(in_array($tag->id, $post_tags)) checked @endif>
            {{ $tag->name }}
        </label>
        @endforeach
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">Save</button>
</div> 