<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::published()
            ->orderBy('sort_order')
            ->orderByDesc('completed_at')
            ->get();

        return view('public.projeler', compact('projects'));
    }

    public function show(string $slug): View
    {
        $project = Project::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $project->load('media');

        $others = Project::published()
            ->where('id', '!=', $project->id)
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('public.proje-detay', compact('project', 'others'));
    }
}
