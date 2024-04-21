<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('is_admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required',
            'body'=>'required',
            'city'=>'required',
            'file'=>'required',
            'category_id'=>'required',
            'food'=>'sometimes',
            'touristAttraction'=>'sometimes',
        ];
    }
    public function createPost()
    {
        $data=array_merge($this->only('title','body','food','touristAttraction','category_id'),[
            'city_id'=>City::create(['name'=>$this->city])->id,
            'user_id'=>auth()->id()
        ]);
      return  Post::create($data);
    }
}
