<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_create_projects()
    {
        $attributes = factory(Project::class)->raw();

        $this->post('/projects', $attributes)
            ->assertRedirect('login');
    }

    /** @test */
    public function guests_may_not_view_projects()
    {
        $this->get('/projects')
            ->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_a_single_project()
    {
        $project = factory(Project::class)->create();

        $this->get($project->path())
            ->assertRedirect('login');
    }

    /** @test */
    public function guest_cannot_view_create_page()
    {
        $this->get('/projects/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $attributes = [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes'       => 'General Notes Here.'
        ];

        $response = $this->actingAs($this->signIn())
            ->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_projets()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($project->path())
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->create();

        $this->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $attribute = ['notes' => 'Changed', 'title' => 'Changed', 'description' => 'Changed'];
        $project   = ProjectFactory::ownedBy($this->signIn())
            ->create();

        $this->patch($project->path(), $attribute)
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attribute);
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes()
    {
        $attribute = ['notes' => 'Changed'];
        $project   = ProjectFactory::ownedBy($this->signIn())
            ->create();

        $this->patch($project->path(), $attribute)
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attribute);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->create();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(\Str::limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $project = ProjectFactory::create();

        $this->actingAs($this->signIn())
            ->get($project->path())
            ->assertForbidden();
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $project = ProjectFactory::create();

        $this->actingAs($this->signIn())
            ->patch($project->path(), $project->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $attributes = factory(Project::class)->raw(['title' => '']);

        $this->actingAs($this->signIn())
            ->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $attributes = factory(Project::class)->raw(['description' => '']);

        $this->actingAs($this->signIn())
            ->post('/projects', $attributes)
            ->assertSessionHasErrors('description');
    }
}
