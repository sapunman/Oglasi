@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-4">
            @include('home.partials.sidebar')
        </div>
        <div class="col-8">
            <h2>Replay</h2>
            <ul class="list-group">
                @foreach ($messages as $message)
                <li class="list-group-item mb-2">
                    <p>Oglas: {{$message->ad->title}} <span class="float-end">{{$message->created_at->format('d M Y')}}</span></p>
                    <p>From: {{$message->sender->name}}</p>
                    <p>{{$message->text}}</p>
                </li>                     
                @endforeach
                <li class="list-group-item mb-2">
                    <form action="{{route('home.replayStore')}}" method="POST">
                        @csrf
                        <input type="hidden" name="sender_id" value="{{$sender_id}}">
                        <input type="hidden" name="ad_id" value="{{$ad_id}}">
                        <textarea name="msg" class="form-control" cols="30" rows="10"></textarea>
                        <button type="submit" class="btn btn-primary form-control">Replay</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
