<?php

namespace App\Http\Controllers\Admin;

use App\Models\Helper;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FilesUploadService;
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
}
