<?php

namespace Tests\Feature;

use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_project', $activity->action);
            $this->assertNull($activity->changes);
        });
    }

    #[Test]
    public function updating_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $originalTitle = $project->title;
        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            $this->assertEquals('updated_project', $activity->action);
            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Changed'],
            ];
            $this->assertEquals($expected, $activity->changes);
        });
    }

    #[Test]
    public function creating_a_task()
    {
        $task = Task::factory()->create();
        $project = $task->project;

        $this->assertCount(2, $task->project->activity);

        tap($task->project->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->action);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    #[Test]
    public function updating_a_task()
    {
        $task = Task::factory()->create();

        $this->assertCount(2, $task->project->activity);
        $task->update(['body' => 'changed']);

        $this->assertCount(3, $task->project->fresh()->activity);
        // $this->assertEquals('updated_task', $task->project->activity->last()->action);
    }

    #[Test]
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => $project->tasks[0]->body,
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->action);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    #[Test]
    public function uncompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => $project->tasks[0]->body,
            'completed' => true,
        ]);

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => $project->tasks[0]->body,
            'completed' => false,
        ]);

        $this->assertCount(4, $project->activity);
        $this->assertEquals('uncompleted_task', $project->activity->last()->action);
    }

    #[Test]
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);

    }
}
