<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Project;
use App\User;

class ProjectInvitationController extends Controller
{
    public function store(ProjectInvitationRequest $request, Project $project)
    {
        $user = User::whereEmail($request->email)->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
