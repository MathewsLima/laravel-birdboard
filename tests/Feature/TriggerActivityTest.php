<?php

namespace Tests\Feature;

use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project  = ProjectFactory::create();
        $activity = $project->activity->last();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created_project', $activity->description);
        $this->assertNull($activity->changes);
    }

    /** @test */
    public function updating_a_project()
    {
        $project       = ProjectFactory::create();
        $originalTitle = $project->title;

        $project->update(['title' => 'Changed']);

        $activity = $project->activity->last();

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated_project', $activity->description);

        $expected = [
            'before' => ['title' => $originalTitle],
            'after'  => ['title' => 'Changed']
        ];

        $this->assertEquals($expected, $activity->changes);
    }

    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some Task');

        $activity = $project->activity->last();

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
        $this->assertEquals('Some Task', $activity->subject->body);
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'Foo Bar',
            'completed' => true
        ]);

        $activity = $project->activity->last();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $activity->description);
        $this->assertInstanceOf(Task::class, $activity->subject);
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())
            ->withTasks(1)
            ->create();

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'Foo Bar',
            'completed' => true
        ]);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);

        $this->patch($project->tasks->first()->path(), [
            'body'      => 'Foo Bar',
            'completed' => false
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);
        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
