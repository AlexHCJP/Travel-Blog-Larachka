<?php

namespace App\Http\Controllers;

use App\Blog;
use App\City;
use App\Country;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png'
        ]);
        $image = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $image);
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => "http://trvel/uploads/$image"
            ]
        ]);
    }
    public function countries(){
        $countries = Country::all();
        return response()->json($countries);
    }
    public function cities(Request $request){
        $country_id = $request->get('country');
        $cities = City::on()->where('country_id', '=', $country_id)->get();
        return response()->json($cities);
    }
    public function blogs(Request $request){
        $country_id = $request->get('country');
        $city_id = $request->get('city');

        $blogs = Blog::on()->whereHas('city', function (Builder $query) use ($city_id, $country_id){
            (!$country_id || $city_id && $country_id)?
                ( ($city_id)? $query->where('id', '=', $city_id) : null)
                :( $query->where('country_id', '=', $country_id));
        })->get();
        return response()->json($blogs);
    }
    public function bestCities(Request $request){

        $count = $this->countRequest($request, 6);

        $country = City::on()
            ->join('blogs', 'blogs.city_id', '=', 'cities.id')
            ->groupBy('cities.id', 'cities.name')
            ->orderByDesc('count')
            ->limit($count)
            ->get([
                'cities.id', 'cities.name', DB::raw('count(blogs.id) as count')
            ]);
        return $country;
    }
    public function lastBlogs(Request $request)
    {
        $count = $this->countRequest($request, 4);

        $blogs = Blog::on()->orderBy('created_at')->limit($count)->get();
        return $blogs;
    }
    public function home()
    {
        $data = [];
        $data['blogs'] = Blog::on()->limit(10)->get(['id', 'title', 'description', 'created_at']);
        $data['cities'] = City::on()->with('country')->get();
        return $data;
    }
    public function blogsByUser(User $user){
        return $user->blog()->get(['id', 'title', 'description', 'created_at', 'updated_at']);
    }
    public function user(User $user){
        $data = [];
        $data['user'] = $user;
        $data['blogs'] = $this->blogsByUser($user);
        return $data;
    }
    public function countRequest(Request $request, int $limit){
        $count = $request->get('count');
        ($count > $limit || $count < 0 || !$count) ? $count = $limit : null;

        return $count;
    }

}
