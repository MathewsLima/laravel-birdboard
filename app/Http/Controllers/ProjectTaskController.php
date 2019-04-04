<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;

class ProjectTaskController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('store', $project);

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $project);

        $attributes = request()->validate(['body' => 'required']);

        $task->update($attributes);

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }
}
