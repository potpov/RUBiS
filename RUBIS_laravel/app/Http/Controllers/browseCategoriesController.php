<?php

namespace App\Http\Controllers;

use App\category;
use App\user;
use App\region;
use Illuminate\Http\Request;

class browseCategoriesController extends Controller {

    public function SwitchUsersTo(Request $request) {
        $filename = strtok(basename($request->getUri()), "?");
        $title = 'RUBiS available categories';
        $cats = Category::all('id', 'name');

        if($cats->count()==0){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'no categories found';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if($request->has('nickname', 'password')){
            //selling
            $user = user::select('id')->where('nickname', $request->nickname)->where('password', $request->password)->get();
            $count = $user->count();
            if ($count != 1) {
                $title = 'RUBiS ERROR: "' . basename($request->getUri()) . '"';
                $message = 'invalid user or password';
                return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
            }
            else{
                return view('browsecatpage',
                    ['code' => 1, 'catList' => $cats, 'userID' => $user->first()->id,
                        'filename' => $filename, 'title' => $title]);
            }
        }
        else if($request->has('region') and region::where('id', $request->region)->count() == 1){
            //search by region if exists
            return view('browsecatpage',
                ['code' => 2, 'catList' => $cats, 'regionID' => $request->region,
                    'filename' => $filename, 'title' => $title]);
        }
        else {
            //default
            return view('browsecatpage',
                ['code' => 3, 'catList' => $cats, 'filename' => $filename, 'title' => $title]);
        }
    }
}
