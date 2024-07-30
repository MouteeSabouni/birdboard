@extends('layouts.app')

@section('content')
        <header class="flex items-center mb-3 py-2">
            <div class="flex justify-between items-end w-full">
                <p class="text-gray-400 text-base">
                    <a href="/projects" class="text-gray-400 text-base no-underline">My Projects</a> / {{ $project->title }}
                </p>

                <div class="space-x-1 flex items-center">
                    @foreach ($project->members as $member)
                        <img class="rounded-full w-7 h-7 mt-2" src="https://gravatar.com/avatar/{{ md5($member->email) }}" alt="{{ $member->name }}'s avatar">
                    @endforeach

                    <div class="flex gap-x-2 pl-6">
                        <x-forms.button>
                            <a class="text-white no-underline" href="{{ $project->path(). '/edit' }}">Edit your project</a>
                        </x-forms.button>

                        <x-forms.button>
                            <a class="text-white no-underline" href="/projects/create">Create a Project</a>
                        </x-forms.button>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="lg:flex">
                <div class="lg:w-4/6 mr-4">
                    <div class="mb-8">
                        <h3 class="text-gray-400 text-lg mb-2">Tasks</h3>
                    {{--tasks--}}
                        <div class="space-y-4">
                            @foreach($project->tasks as $task)
                                <div class="bg-white rounded-lg p-3">
                                    <form method="POST" action={{ $task->path() }}>
                                        @method('PATCH')
                                        @csrf

                                        <div class="flex">
                                            <input name="body" type="text" class="w-full {{ $task->completed ? 'text-gray-400' : '' }}" value="{{ $task->body }}">
                                            <input name="completed" type="checkbox" onchange=this.form.submit() {{ $task->completed ? 'checked' : '' }}>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                                <div class="bg-white rounded-lg p-3">
                                    <form action="{{ $project->path() . '/tasks' }}" method="POST">
                                        @csrf
                                        <input type="text" placeholder="Add a new task..." class="w-full" name="body">
                                    </form>
                                </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-gray-400 text-lg mb-2">General Notes</h3>

                        <form action="{{ $project->path() }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <textarea class="bg-white rounded-lg shadow p-3 w-full"
                                    placeholder="Add a new note..."
                                    style="min-height: 200px"
                                    name="notes"
                            >{{ $project->notes }}</textarea>

                            <x-forms.button class="text-white" type="submit">Save Note</x-forms.button>
                        </form>

                        @include('components.forms.errors')
                    </div>
                </div>

                <div class="lg:w-2/6 mt-9 space-y-4">
                    <x-card :$project />

                    @include('projects.activity.card')

                    @can('manage', $project)
                        @include('projects.invite')
                    @endcan

                </div>


            </div>
        </main>

@endsection
