<?php

namespace App\Http\Controllers;


use App\items;
use Illuminate\Http\Request;
use \DB;

class searchItemsController extends Controller
{
    public function ByRegion(Request $request){
        $required = array('categoryName', 'category', 'region',);
        $title = 'RUBiS: Search items by region';
        $filename = strtok(basename($request->getUri()), "?");

        if($request->has($required)){
            $nbOfItems = $request->input('nbOfItems', 25);
            $items = DB::table(DB::raw('items, users'))
                ->select(DB::raw('items.*'))
                ->whereRaw('items.category='.$request->category)
                ->whereRaw('items.seller=users.id')
                ->whereRaw('users.region='.$request->region)
                ->whereRaw('end_date>=NOW()')
                ->simplePaginate($nbOfItems);
            return view('searches.byregion', ['items' => $items,
                'title' => $title,
                'filename' => $filename,
                'RegionID' => $request->region,
                'CatID' => $request->category,
                'CatName' => $request->categoryName,
                'nbOfItems' => $nbOfItems
            ]);
        }
        else{
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }

    }

    public function ByCategory(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if($request->has('categoryName') or $request->has('category')) {
            $nbOfItems = $request->input('nbOfItems', 25);
            $title = 'RUBiS: Items in category' . $request->catName;
            $items = items::where('category', $request->category)
                    ->whereDate('end_date', '>=', date('Y-m-d H:i:s'))
                    ->simplePaginate($nbOfItems);
            return view('searches.bycategory', ['items' => $items,
                'title' => $title,
                'filename' => $filename,
                'CatID' => $request->category,
                'CatName' => $request->categoryName,
                'nbOfItems' => $nbOfItems
            ]);
        }
        else{
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
    }
}
