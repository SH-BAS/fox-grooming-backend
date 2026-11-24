<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
class ApiCmsPageController extends Controller
{
    public function index(Request $request)
    {
        try {

            if ($request->filled('page_key')) {

                $page = CmsPage::where('page_key', $request->page_key)->first();

                if (!$page) {
                    return $this->error('Page not found', 404);
                }

                return $this->success($page, 'Page fetched successfully');
            }

            $pages = CmsPage::get();

            return $this->success($pages, 'CMS pages fetched successfully');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
