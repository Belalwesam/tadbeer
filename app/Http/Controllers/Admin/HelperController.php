<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HelperStoreRequest;

class HelperController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('admin.pages.helpers.index' , compact('categories'));
    }
    public function store(HelperStoreRequest $request) {
        return $request->all();
    }
}
