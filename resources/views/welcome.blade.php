@extends('layouts.main')

@section('content')
    <div class="title m-b-md">
        Rhino
    </div>

    <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
@endsection