<?php

namespace App\Http\Controllers\Admin;

use App\Models\Helper;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FilesUploadService;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\HelperStoreRequest;

class HelperController extends Controller
{

    private $filesUploadService;
    public function __construct(FilesUploadService $filesUploadService)
    {
        $this->filesUploadService = $filesUploadService;
    }
    public function index()
    {
        $categories = Category::all();
        return view('admin.pages.helpers.index', compact('categories'));
    }
    public function store(HelperStoreRequest $request)
    {
        // return $this->filesUploadService->test();
        // take validated data
        $data['name'] = $request->validated('name');
        $data['age'] = $request->validated('age');
        $data['nationality'] = $request->validated('nationality');
        $data['category_id'] = $request->validated('category_id');
        $data['sku'] = Str::random(4) . '-' . uniqid();


        if ($request->hasFile('video')) {
            $data['video'] = $this->filesUploadService->uploadFile($request->file('video'), '/videos');
        }
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->filesUploadService->uploadFile($request->file('avatar'), '/avatars');
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $this->filesUploadService->uploadFile($request->file('resume'), '/resumes');
        }
        Helper::create($data);
        return http_response_code(200);
    }

    public function getHelpersList(Request $request)
    {
        $helpers = Helper::orderBy('id', 'desc')->get();

        # modify the look of some data and controllers 
        $helpers->map(function ($helper) {
            // localized sentences
            $edit_text = trans('general.edit');
            $delete_text = trans('general.delete');

            // modify the avatar video , and resume endpoints to be a url
            $helper->avatar = asset(Storage::url($helper->avatar));
            $helper->video = route('admin.helpers.helpers.show_video', $helper->id);
            $helper->resume = asset(Storage::url($helper->resume));
            $helper->category = $helper->category;

            // check if the admin has ability to do these actions
            if (auth('admin')->user()->hasAbilityTo('edit helpers')) {
                $helper->actions = <<<HTML
                    <div class="dropdown btn-pinned">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                <li>
                                    <a class="dropdown-item edit-btn"
                                    data-id="{$helper->id}"
                                    data-name = "{$helper->name}"
                                    data-bs-toggle="modal"
                                    data-bs-target = "#editCategoryModal"
                                    href="javascript:void(0);">
                                <i class="bx bx-edit me-0 me-2 text-primary"></i>
                                        {$edit_text}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item delete-btn"
                                        data-id = "{$helper->id}"
                                        href="javascript:void(0);">
                                        <i class="bx bx-trash me-0 me-2 text-danger"></i>
                                        {$delete_text}
                                    </a>
                                </li>
                            </ul>
                    </div>
            HTML;
            } else {
                $helper->actions = '';
            }

            return $helper;
        });
        return $helpers;
    }

    public function getHelperVideo(Helper $helper)
    {
        return view('admin.pages.helpers.show-video', compact('helper'));
    }
}
