<?php

namespace App\Http\Controllers;

use App\category;
use App\items;
use Illuminate\Http\Request;
use App\user;

class sellController extends Controller
{
    function FormSeller(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('category') or !$request->has('user')){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if(user::where('id', $request->user)->count()!=1 or category::where('id', $request->category)->count()!=1) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        $title = 'RUBiS: Sell your item';
        return view('sell.form', [
                    'title' => $title,
                    'filename' => $filename,
                    'userID' => $request->user,
                    'catID' => $request->category
        ]);
    }

    function StoreSell(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        $required = array('userId' , 'categoryId', 'name', 'initialPrice', 'reservePrice',
            'buyNow', 'duration', 'quantity', 'description');
        if(!$request->has($required)){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }

        $newItem = new items();
        $newItem->name = $request->name;

        $newItem->start_date = date("Y:m:d H:i:s");
        $newItem->end_date = date("Y:m:d H:i:s", mktime(date("H"), date("i"),date("s"), date("m"), date("d")+$request->duration, date("Y")));

        if($request->description == NULL or empty($request->description))
            $newItem->description = "No description";
        else
            $newItem->description = $request->description;

        $newItem->category = $request->categoryId;
        $newItem->initial_price = $request->initialPrice;
        $newItem->reserve_price = $request->reservePrice;
        $newItem->quantity = $request->quantity;
        $newItem->buy_now = $request->buyNow;
        $newItem->seller = $request->userId;
        $newItem->save();
        $title = 'RUBiS: Selling ' . $request->name;
        $fields = $request->all();
        return view('sell.store', [
            'item' => $fields,
            'filename' => $filename,
            'title' => $title
        ]);
    }
}
