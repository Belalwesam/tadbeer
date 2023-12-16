<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HelperStoreRequest;
use App\Models\Helper;

class HelperController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.pages.helpers.index', compact('categories'));
    }
    public function store(HelperStoreRequest $request)
    {
        // take validated data
        $data['name'] = $request->validated('name');
        $data['age'] = $request->validated('age');
        $data['nationality'] = $request->validated('nationality');
        $data['category_id'] = $request->validated('category_id');
        $data['sku'] = Str::random(4) . '-' . uniqid();

        // initaite a helper to work on avatar , video , and resume
        $helper = Helper::create($data);
        return http_response_code(200);
    }
}
