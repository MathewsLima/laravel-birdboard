<?php

namespace Tests\Feature;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_project()
    {
        $project = factory(Project::class)->create();

        $this->post($project->path() . '/tasks')
            ->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory(Project::class)->create();
        $task    = factory(Task::class)->raw();

        $this->post($project->path() . '/tasks', $task)
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_tasks()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed'])
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', [
            'body' => 'changed'
        ]);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())
            ->assertSee('Test Task');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'changed']);

        $this->assertDatabaseHas('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body'      => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'changed',
            'completed' => true
        ]);

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', [
            'body'      => 'changed',
            'completed' => false
        ]);
    }

    /** @test */
    public function a_project_requires_a_body()
    {
        $project    = ProjectFactory::ownedBy($this->signIn())->create();
        $attributes = factory(Task::class)->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
