@extends('layouts.app')

@section('content')
    <h2>Update your project</h2>

    <form action={{ $project->path() }} method="POST" class="container">
        @method('PATCH')

        <x-forms.form :$project buttonText="Update" />
    </form>
@endsection
