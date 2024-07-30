<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function non_owners_cannot_invite_users()
    {
        $project = ProjectFactory::create();
        $user = $this->signIn();

        $this->actingAs($user)
            ->post($project->path().'/invite')
            ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)
            ->post($project->path().'/invite')
            ->assertStatus(403);
    }

    #[Test]
    public function a_project_owner_can_invites_users(): void
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $invitedUser = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invite', ['email' => $invitedUser->email])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($invitedUser));
    }

    #[Test]
    public function the_invited_user_must_have_a_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invite', ['email' => 'notauser@gmail.com'])
            ->assertSessionHasErrors([
                'email' => 'The invited user must have a birdboard account.',
            ], null, 'invitations');
    }

    #[Test]
    public function incited_users_may_udpate_project_detials()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);
        $this->post($project->path().'/tasks', $task = ['body' => 'Task created by the invited user']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
