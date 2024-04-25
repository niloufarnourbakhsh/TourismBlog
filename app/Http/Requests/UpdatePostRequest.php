<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePostRequest extends FormRequest
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
            'city'=>'sometimes',
            'city_id'=>'required',
            'category_id'=>'required',
            'food'=>'sometimes',
            'touristAttraction'=>'sometimes',
            'file'=>'sometimes',
        ];
    }

    public function updateCity()
    {
        return City::whereId($this->city_id)->update(['name'=>$this->city]);
    }
    public function save()
    {
        if ($this->city){
            $this->updateCity();
        }
        return tap($this->route('post')->update($this->except(['city','city_id','file'])));
    }
}
