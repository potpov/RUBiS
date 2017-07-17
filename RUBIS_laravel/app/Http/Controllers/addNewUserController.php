<?php

namespace App\Http\Controllers;

use App\region;
use App\user;
use Illuminate\Http\Request;

class addNewUserController extends Controller
{
    public function AddNewUser(Request $request){

        $filename = strtok(basename($request->getUri()), "?");
        $requiredFields = array('firstname', 'lastname', 'nickname', 'email', 'password', 'region');

        if(!$request->has($requiredFields)) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'please fill all the required fields';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        //checking region
        $region = Region::select('id')->where('name', $request->region)->get();
        if($region->count()!=1){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'please select a valid region';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if(User::where('nickname', $request->nickname)->count()){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'username not available';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        //everything ok creating new user to database
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->nickname = $request->nickname;
        $user->password = $request->password;
        $user->region = $region->first()->id;
        $user->balance = 0;
        $user->rating = 0;
        $user->email = $request->email;
        $user->creation_date = date("Y:m:d H:i:s");
        $user->save();
        $title = 'RUBiS: Welcome to' . $user->nickname;
        return view('welcome.newuser', ['user' => $user, 'title' => $title, 'filename' => $filename]);
    }
}
