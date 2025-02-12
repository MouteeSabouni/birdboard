<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_a_path()
    {
        $project = Project::factory()->create();
        $this->assertEquals("/projects/$project->id", $project->path());
    }

    #[Test]
    public function a_project_belongs_to_an_owner()
    {
        $project = Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->owner);
    }

    #[Test]
    public function a_project_can_add_a_task()
    {
        $project = Project::factory()->create();
        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    #[Test]
    public function a_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($invitedUser = User::factory()->create());

        $this->assertTrue($project->members->contains($invitedUser));
    }
}
