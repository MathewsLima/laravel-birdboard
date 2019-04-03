@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a Project</h1>

        <form action="/projects" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-primary">Submit</button>
                <a href="/projects" class="btn btn-link">Cancel</a>
            </div>

        </form>
    </div>
@endsection