<?php

namespace App\Http\CoreApi\ProjectPage\GetPage;

use App\Http\CoreApi\ApiController;
use App\Models\Page;
use App\Models\Project;

class Controller extends ApiController
{
    public function __invoke(Request $request, Project $project, Page $page)
    {
        if ($page->project_id !== $project->id) {
            abort(404, '페이지를 찾을 수 없습니다.');
        }

        return $this->success($page->load(['parent', 'children', 'user']));
    }
}
