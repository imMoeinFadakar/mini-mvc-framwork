@extends('layouts.master')

@section('title' , 'Edit Article')

@section('content')
    <h2>{{ $title }}</h2>

    @if($auth)
        <span>you are logged in</span>
    @else
        <span>you are not logged in</span>
    @endif

    <form action="/articles/create?id=12" method="post">
        <input type="text" name="title" placeholder="enter article title">
        <button type="submit">create</button>
    </form>
@endsection
