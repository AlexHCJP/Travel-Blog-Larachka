<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function show(City $city)
    {
        $data['city'] = $city->load('country');
        $data['blogs'] = $city->blog()->get(['id', 'title', 'description', 'user_id']);
        return $data;
    }
}
