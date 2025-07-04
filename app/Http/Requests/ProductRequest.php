<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route()->product->id ?? null;
        return [
            'name' => 'required|unique:products,name,' . $id,
            'price' => 'required',
            'media' => 'nullable'
        ];
    }

    public function attributes()
    {
        return[
            // 'name' => 'ههه',
            'price' => __('keywords.price'),
        ];
    }

    // public function messages()
    // {
    //     return[
    //         'name.unique' => 'لو عايز اخصص مثلا لو الاسم معمول قبل كدا يطلعلي رساله معينه',
    //     ];
    // }
}
