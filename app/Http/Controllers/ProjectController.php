<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index')->withProjects($projects);
    }

    public function show(Project $project)
    {
        abort_if(auth()->user()->isNot($project->user), Response::HTTP_FORBIDDEN);

        return view('projects.show')->withProject($project);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }
}
