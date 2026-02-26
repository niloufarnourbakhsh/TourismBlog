<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityCreateRequest;
use App\Models\City;
use Illuminate\Http\Request;

class cityController extends Controller
{
    public function index()
    {
        $cities=City::query()->paginate(7);
        return view('Admin.city')->with('cities',$cities);
    }

    public function store(CityCreateRequest $request)
    {
        City::create(['name'=>$request->name]);
        return redirect()->back();
    }

    public function update(City $city, CityCreateRequest $request)
    {
        $city->update(['name'=>$request->name]);
        return redirect()->back();
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->back();
    }
}
