<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index')->withProjects($projects);
    }

    public function show(Project $project)
    {
        return view('projects.show')->withProject($project);
    }

    public function store(Request $request)
    {
        $attributes = request()->validate([
            'title'       => 'required',
            'description' => 'required',
        ]);

        // $attributes['user_id'] = auth()->id();

        auth()->user()->projects()->create($attributes);

        // Project::create($attributes);

        return redirect('/projects');
    }
}
