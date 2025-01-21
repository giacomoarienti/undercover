@extends('layouts.app')

@section('content')
    @foreach ($product->getMedia() as $media)
        <p>{{ $media->name }}</p>
        <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
    @endforeach
@endsection
