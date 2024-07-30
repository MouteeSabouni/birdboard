<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Factories\ProjectFactory;
use Facades\Tests\Setup\ProjectFactory as ProjectTestFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function guests_cannot_manage_projects(): void
    {
        //        $this->withoutExceptionHandling();
        $project = (new ProjectFactory())->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');

        $this->get($project->path().'/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    #[Test]
    public function a_user_can_create_a_project(): void
    {
        $this->signIn();

        $response = $this->followingRedirects()
            ->post('/projects', $attributes = Project::factory()->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSeeInOrder([$attributes['notes'], '</textarea>'], false);
    }

    #[Test]
    public function unauthorized_users_cannot_delete_projects()
    {
        $project = ProjectTestFactory::create();
        $this->delete($project->path())->assertRedirect('login');

        $user = $this->signIn();

        $assertDeleteForbidden = function () use ($project, $user) {
            $this->actingAs($user)->delete($project->path())->assertForbidden();
        };

        $assertDeleteForbidden($project, $user);

        $project->invite($user);
        $assertDeleteForbidden($project, $user);
    }

    #[Test]
    public function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
        $project = ProjectTestFactory::ownedBy(auth()->user())->create();

        $this->actingAs($project->owner)->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    #[Test]
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $project = ProjectTestFactory::ownedBy(auth()->user())->create();
        $attributes = [
            'title' => 'changed',
            'description' => 'changed as well',
            'notes' => 'also changed'];

        $this->get($project->path().'/edit')->assertOk();
        $this->patch($project->path(), $attributes)->assertRedirect($project->path());
        $this->get($project->path())->assertSee($attributes);
        $this->assertDataBaseHas('projects', $attributes);
    }

    #[Test]
    public function a_user_can_update_projects_notes()
    {
        $this->signIn();
        $project = ProjectTestFactory::ownedBy(auth()->user())->create();
        $attributes = ['notes' => 'changed'];

        $this->patch($project->path(), $attributes)->assertRedirect($project->path());
        $this->get($project->path())->assertSee($attributes['notes']);
        $this->assertDataBaseHas('projects', $attributes);

    }

    #[Test]
    public function a_user_can_view_their_project(): void
    {
        $this->signIn();
        $project = ProjectTestFactory::ownedBy(auth()->user())->create();

        $this->get($project->path())->assertSee([$project->title, $project->description]);

    }

    #[Test]
    public function invited_users_can_see_projects_they_were_invited_to()
    {
        $invitedUser = (new User)->factory()->create();
        $project = tap((new ProjectFactory())->create())->invite($invitedUser);

        $this->actingAs($invitedUser)->get('/projects')->assertSee($project->title);
    }

    #[Test]
    public function an_authenticated_user_cannot_view_projects_of_others(): void
    {
        $this->signIn();
        //        $this->withoutExceptionHandling();
        $project = (new ProjectFactory())->create();
        $this->get($project->path())->assertForbidden();
    }

    #[Test]
    public function an_authenticated_user_cannot_update_projects_of_others(): void
    {
        $this->signIn();
        //        $this->withoutExceptionHandling();
        $project = (new ProjectFactory())->create();
        $this->patch($project->path())->assertForbidden();

    }

    #[Test]
    public function a_project_requires_a_title(): void
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    #[Test]
    public function a_project_requires_a_description(): void
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
