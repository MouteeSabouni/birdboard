<?php

namespace Tests\Feature;

use Database\Factories\TaskFactory;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = ProjectFactory::create();

        $this->post($project->path().'/tasks')->assertRedirect('login');
    }

    #[Test]
    public function only_the_project_owner_can_add_tasks()
    {
        $this->signIn();
        $project = ProjectFactory::create();

        $this->post($project->path().'/tasks', ['body' => 'Test task'])->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    #[Test]
    public function only_the_project_owner_can_update_tasks()
    {
        $this->signIn();
        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    #[Test]
    public function a_project_has_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path().'/tasks', ['body' => 'Test task']);

        $this->get($project->path())->assertSee('Test task');

    }

    #[Test]
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();
        $attributes = (new TaskFactory)->raw(['body' => null]);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    #[Test]
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create(); //treating it as a real-time facade

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), ['body' => 'Updated task']);
        $this->assertDatabaseHas('tasks', ['body' => 'Updated task']);
    }

    #[Test]
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create(); //treating it as a real-time facade

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'Updated task',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Updated task',
            'completed' => true,
        ]);
    }

    #[Test]
    public function a_task_can_be_uncompleted()
    {
        $project = ProjectFactory::withTasks(1)->create(); //treating it as a real-time facade

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'Updated task',
                'completed' => true,
            ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'Updated task',
                'completed' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Updated task',
            'completed' => false,
        ]);
    }
}
