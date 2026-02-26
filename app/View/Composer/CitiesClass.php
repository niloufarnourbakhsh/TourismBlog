<?php

namespace App\View\Composer;

use App\Models\City;
use Illuminate\View\View;

class CitiesClass
{
    public function compose(View $view)
    {
       $view->with('cities',City::all());
    }

}
