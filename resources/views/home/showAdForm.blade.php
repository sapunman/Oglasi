@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-4">
            @include('home.partials.sidebar')
        </div>
        <div class="col-8">
            <form action="{{route('home.saveAd')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="title" placeholder="title" class="form-control"><br>
            <textarea name="body" class="form-control" placeholder="body" cols="30" rows="10"></textarea><br>
            <input type="number" name="price" class="form-control" placeholder="price"><br>
            <input type="file" name="image1" class="form-control">
            <input type="file" name="image2" class="form-control">
            <input type="file" name="image3" class="form-control"><br>
            <select name="category" class="form-control">
                @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select><br>
            <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
