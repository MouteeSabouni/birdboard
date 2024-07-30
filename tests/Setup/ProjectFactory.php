<?php

namespace Tests\Setup;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    protected $user;

    protected $tasksCount = 0;

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    public function withTasks($tasksCount)
    {
        $this->tasksCount = $tasksCount;

        return $this;
    }

    public function create()
    {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory(),
        ]);

        Task::factory($this->tasksCount)->create([
            'project_id' => $project->id,
        ]);

        return $project;
    }
}
