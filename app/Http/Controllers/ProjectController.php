<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::published()->ordered()->get();
        $categories = $projects->pluck('category')->unique()->values();

        return view('pages.projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'activeCategory' => $request->query('category'),
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->is_published, 404);

        return view('pages.projects.show', [
            'project' => $project,
            'next' => Project::published()
                ->whereKeyNot($project->id)
                ->ordered()
                ->first(),
        ]);
    }
}
