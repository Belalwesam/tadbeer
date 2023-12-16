<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HelperStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    //modify the category name from category to category_id
    public function prepareForValidation()
    {
        $this->merge([
            'category_id' => $this->category
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'nationality' => 'required|string',
            'category_id' => 'required|integer',
            'age' => 'required|integer',
            'video' => 'required|mimes:mp4',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'resume' => 'required|mimes:pdf|max:2048'
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => trans('helpers.category'),
            'name' => trans('helpers.name'),
            'age' => trans('helpers.age'),
            'resume' => trans('helpers.resume'),
            'nationality' => trans('helpers.nationality'),
            'video' => trans('helpers.video'),
            'avatar' => trans('helpers.avatar'),
        ];
    }
}
