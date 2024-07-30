<div class="mt-3 bg-white rounded-lg shadow p-4" :$project>
        <span class="text-lg font-bold">Project actions:</span>
        <div class="pl-4">
            @foreach ($project->activity as $activity)
            <li class="mb-1 text-xs">
                @include("projects.activity.{$activity->action}")
                <span class="text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
            </li>
            @endforeach
        </div>
</div>
