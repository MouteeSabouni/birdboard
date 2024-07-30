<div {{ $attributes(['class' => 'bg-white rounded-lg shadow p-4']) }}>
    <h3 class="font-normal text-xl py-3 -ml-6 mb-2 border-l-4 border-cyan pl-5">
        <a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a>
    </h3>

    <div class="text-gray-400"> {{ Illuminate\Support\Str::limit($project->description, 100) }}</div>

    @can('manage', $project)
        <footer class="mt-3 text-right">
            <form method="POST" action="{{ $project->path() }}">
                @method('DELETE')
                @csrf

                <button class='bg-red-700 text-white rounded-lg mt-2 py-2 px-3 font-semibold text-sm shadow'>Delete</button>
            </form>
        </footer>
    @endcan
</div>
