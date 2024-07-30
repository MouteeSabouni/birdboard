@extends('layouts.app')

@section('content')


    <header class="flex items-center mb-3 py-2">
        <div class="flex justify-between items-end w-full">
            <h3 class="text-gray-400 text-base">My Projects</h3>
            <x-forms.button>
                <a class="text-white no-underline" href="/projects/create">Create a Project</a>
            </x-forms.button>
        </div>
    </header>

    <main class="lg:flex lg:flex-wrap -mx-4">
        @forelse($projects as $project)
            <div class="lg:w-1/3 px-3 pb-6">
                <x-card style="height: 200px" :$project />
            </div>
        @empty
            <div>No Projects Yet.</div>
        @endforelse
    </main>
@endsection
