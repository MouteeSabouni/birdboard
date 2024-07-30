<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;

class ProjectTasksController extends Controller
{
    public function store(Project $project): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {

        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());

    }

    public function update(Project $project, Task $task)
    {
        $this->authorize($task->project); //It will be auto-translated by Laravel

        $task->update(request()->validate(['body' => 'required']));

        request('completed') ? $task->complete() : $task->uncomplete();

        return redirect($project->path());
    }
}
