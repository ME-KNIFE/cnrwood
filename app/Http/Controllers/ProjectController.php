<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::published()
            ->orderByDesc('is_featured')
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

        $project->loadMissing('media');

        $others = Project::published()
            ->where('id', '!=', $project->id)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('public.proje-detay', compact('project', 'others'));
    }
}
