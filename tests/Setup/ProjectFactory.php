<?php

namespace Tests\Setup;

use App\Project;
use App\Task;
use App\User;

final class ProjectFactory
{
    private $taskCount = 0;

    private $user;

    public function withTasks(int $count): self
    {
        $this->taskCount = $count;

        return $this;
    }

    public function ownedBy(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function create(): Project
    {
        $project = factory(Project::class)->create([
            'user_id' => $this->user ?? factory(User::class)
        ]);

        factory(Task::class, $this->taskCount)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}
