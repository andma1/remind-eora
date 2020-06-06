<?php


namespace App\Http\Requests\User;



use App\Http\Requests\Request;
use App\Image;
use Illuminate\Support\Facades\Auth;

class StoreImageRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string',
            'content' => 'required|string'
        ];
    }

    public function handle(): Image
    {
        return Image::store(Auth::user(), $this->get('content'), $this->get('name'));
    }
}
