<?php  $project = new App\Models\Project;  ?>

@extends('layouts.app')

@section('content')
    <h2>Create a new project</h2>

    <form method="POST" action='/projects' class="container">
        <x-forms.form buttonText="Create" :$project />
    </form>
@endsection
