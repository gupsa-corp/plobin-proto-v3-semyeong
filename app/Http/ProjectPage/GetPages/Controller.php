<?php

namespace App\Http\ProjectPage\GetPages;

use App\Http\Controllers\ApiController;
use App\Models\Project;

class Controller extends ApiController
{
    public function __invoke(Request $request, Project $project)
    {
        $pages = $project->rootPages()->with(['children', 'user'])->get();
        
        return $this->success($pages);
    }
}