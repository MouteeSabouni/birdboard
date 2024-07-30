<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;
//Changing the TestCase to the one above has removed the error of 'A facade root has not been set.'

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_has_projects()
    {
        $user = (new User)->factory()->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    #[Test]
    public function a_user_has_accessible_projects()
    {
        $userOne = (new User)->factory()->create();
        $userTwo = (new User)->factory()->create();
        $userThree = (new User)->factory()->create();

        $userOneProject = ProjectFactory::ownedBy($userOne)->create();
        $this->assertCount(1, $userOne->accessibleProjects());

        $userOneProject->invite($userTwo);
        $this->assertCount(1, $userTwo->accessibleProjects());

        ProjectFactory::ownedBy($userTwo)->create()->invite($userThree);
        $this->assertCount(1, $userOne->accessibleProjects());

        $userOneProject->invite($userThree);
        $this->assertCount(2, $userThree->accessibleProjects());
    }
}
