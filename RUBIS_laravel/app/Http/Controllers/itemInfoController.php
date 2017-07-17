<?php

namespace App\Http\Controllers;

use App\bid;
use App\items;
use App\user;
use Illuminate\Http\Request;
use \DB;

class itemInfoController extends Controller
{

    public function itemBidHistory(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if($request->has('itemId') and items::where('id', $request->itemId)->count() == 1){
            $item = items::where('id', $request->itemId)->first();
            $bids = DB::table(DB::raw('bids, users'))
                ->select(DB::raw('bids.*, users.nickname as sellerUser'))
                ->whereRaw('bids.user_id=users.id')
                ->whereRaw('bids.item_id='. $request->itemId)
                ->orderBy('date', 'desc')
                ->get();
            return view('bids_information', [
                'item' => $item,
                'bids' => $bids,
                'filename' => $filename,
                'title' => "RUBiS: Bid history for $item->name."
            ]);
        } else{
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
    }



    public function itemInfoPage(Request $request){
        //check if itemId is ok
        $filename = strtok(basename($request->getUri()), "?");
        if($request->has('itemId') and items::where('id', $request->itemId)->count() == 1){
            //getting item informations
            $item = items::where('id', $request->itemId)->first();
            //bidding informations
            $nbOfBids = bid::where('item_id', $request->itemId)->count();
            if($nbOfBids == 0) {
                $current = $item->initial_price;
                $first = "none";
            }
            else {
                $current = bid::where('item_id', $request->itemId)->max('bid');
                $first = bid::where('item_id', $request->itemId)->min('bid');
            }
            $title = "RUBiS: Viewing" . $item->name;
            $sellerUser = user::select('nickname')->where('id', $item->seller)->first();
            return view('item_information', [
                'item' => $item,
                'title' => $title,
                'filename' => $filename,
                'nbOfBids' => $nbOfBids,
                'current' => $current,
                'first' => $first,
                'sellerUser' => $sellerUser->nickname
            ]);
        } 
        else{
                $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
                $message = 'required parameters missed';
                return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
    }
}
