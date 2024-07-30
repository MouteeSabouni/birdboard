<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(): View
    {

        $projects = auth()->user()->accessibleProjects();

        return view('projects.index', [
            'projects' => $projects,
            'user' => auth()->user(),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.show', ['project' => $project]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $project = auth()->user()->projects()->create($this->validate());

        return redirect($project->path());
    }

    // public function store(CreateProjectRequest $request): RedirectResponse
    // {
    //     $project = auth()->user()->create($request->validated());
    //     return redirect($project->path());
    // }

    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project]);
    }

    public function update(UpdateProjectRequest $request): RedirectResponse
    {

        return redirect($request->save()->path());
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('manage', $project);
        $project->delete();

        return redirect('/projects');
    }

    protected function validate(): array
    {
        return request()->validate([
            'title' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string|min:5',
            'notes' => 'nullable|string|min:3|max:255',
        ]);
    }
}
