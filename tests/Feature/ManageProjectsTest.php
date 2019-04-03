<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private function login()
    {
        return $this->actingAs(factory(User::class)->create());
    }

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
            'description' => $this->faker->paragraph
        ];

        $this->login()
            ->post('/projects', $attributes)
            ->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('projects')
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->withoutExceptionHandling();

        $this->login();

        $project = factory(Project::class)->create(['user_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $project = factory(Project::class)->create();

        $this->login()
            ->get($project->path())
            ->assertForbidden();
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $attributes = factory(Project::class)->raw(['title' => '']);

        $this->login()
            ->post('/projects', $attributes)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $attributes = factory(Project::class)->raw(['description' => '']);

        $this->login()
            ->post('/projects', $attributes)
            ->assertSessionHasErrors('description');
    }
}
