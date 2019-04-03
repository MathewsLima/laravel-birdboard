<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Response;

class ProjectTaskController extends Controller
{
    public function store(Project $project)
    {
        abort_if(auth()->user()->isNot($project->user), Response::HTTP_FORBIDDEN);

        request()->validate(['body' => 'required']);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        abort_if(auth()->user()->isNot($project->user), Response::HTTP_FORBIDDEN);

        request()->validate(['body' => 'required']);

        $task->update([
            'body'      => request('body'),
            'completed' => request()->has('completed')
        ]);

        return redirect($project->path());
    }
}
