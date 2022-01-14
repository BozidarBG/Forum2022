@extends('layouts.users')

@section('title')
create new topic
@endsection

@section('content')
<div class="container mb-5">
    <h2 class="heading-2 my-4">Create New Topic</h2>
    <div class="row">
        <div class="col-12 card p-5">
            <x-form-success key="topic_created" />
            <form action="{{route('users.topic.store')}}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Topic title</label>
                    <x-form-errors name="title"/>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Topic title" value="{{old('title')}}">
                </div>
                <div class="mb-3 my-4">
                    <label class="form-label">Description</label>
                    <x-form-errors name="description" />
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{old('description')}}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12 my-4">
                        <label class="form-label">Select one category</label>
                        <x-form-errors name="category" />
                        <select class="form-select @error('category') is-invalid @enderror" aria-label="Default select example" name="category">
                            <option value="">Please, select one category</option>
                            @foreach($categories as $category)
                            <option @if($category->id == old('category')) selected="selected" @endif  value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                          </select>
                    </div>
                    <div class="col-md-6 col-sm-12 my-4">
                        <label class="form-label">Select one or more tags</label>
                        <x-form-errors name="tags" />
                          <select class="form-select @error('tags') is-invalid @enderror" multiple aria-label="multiple select example" name="tags[]">
                            <option value="">Please, select one or more tags</option>
                            @foreach($tags as $tag)
                            <option @if(old('tags') !==null && in_array($tag->id, old('tags'))) selected="selected" @endif  value="{{$tag->id}}">{{$tag->name}}</option>
                            @endforeach
                          </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 my-4">
                        <input type="submit" name="submit" class="btn btn-primary" value="Save Topic">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
