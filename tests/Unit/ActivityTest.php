<?php

namespace Tests\Unit;

use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_has_a_user()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->assertEquals(auth()->id(), $project->activity->first()->user_id);
    }
}
