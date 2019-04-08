<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    private function assertInvitationForbidden(User $user, Project $project)
    {
        $this->actingAs($user)
            ->post($project->path() . '/invitations')
            ->assertForbidden();
    }

    /** @test */
    public function non_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();

        $user = factory(User::class)->create();

        $this->assertInvitationForbidden($user, $project);

        $project->invite($user);

        $this->assertInvitationForbidden($user, $project);
    }

    /** @test */
    public function a_project_owner_can_invite_a_user()
    {
        $userToInvite = factory(User::class)->create();
        $project      = ProjectFactory::create();

        $this->actingAs($project->user)
            ->post($project->path() . '/invitations', [
                'email' => $userToInvite->email
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->user)
            ->post($project->path() . '/invitations', [
                'email' => 'not_a_user@email.com'
            ])
            ->assertSessionHasErrors([
                'email' => 'The User you are inviting must have a Birdboard account.'
            ], null, 'invitations');
    }

    /** @test */
    public function invited_users_may_update_projects_details()
    {
        $project = ProjectFactory::create();
        $newUser = factory(User::class)->create();

        $project->invite($newUser);

        $url  = action('ProjectTaskController@store', $project);
        $task = ['body' => 'Foo Task'];

        $this->signIn($newUser);

        $this->post($url, $task);

        $this->assertDatabaseHas('tasks', $task);
    }
}
