<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[test]
    public function it_belongs_to_a_project()
    {
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    #[test]
    public function it_has_a_path()
    {
        $task = (new TaskFactory)->create();

        $this->assertEquals('/projects/'.$task->project->id.'/tasks/'.$task->id, $task->path());
    }

    #[Test]
    public function it_can_be_completed()
    {
        $task = (new TaskFactory)->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->completed);
    }

    public function it_can_be_uncompleted()
    {
        $task = (new TaskFactory)->create(['completed' => true]);

        $task->uncomplete();

        $this->assertFalse($task->completed);
    }
}
