<?php

namespace App\Http\Controllers;

use App\bid;
use App\items;
use App\user;
use Illuminate\Http\Request;

class bidsController extends Controller
{
    public function BidAuth(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('itemId') or items::where('id', $request->itemId)->count()!=1){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        else
            return view('bid.auth', [
                'filename' => $filename,
                'title' => 'RUBiS: User authentification for bidding',
                'itemID' => $request->itemId
            ]);
    }

    public function BidForm(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('itemId') or items::where('id', $request->itemId)->count()!=1){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        else {
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
            //get bidder and seller informations
            $bidder = user::where('nickname', $request->nickname)->where('password', $request->password)->first();
            $seller = user::find($item->seller);
            $title = 'RUBiS: Bidding';
            return view('bid.form', [
                'filename' => $filename,
                'title' => $title,
                'bidder' => $bidder,
                'seller' => $seller,
                'item' => $item,
                'current' => $current,
                'first' => $first,
                'nbBids' => $nbOfBids
            ]);
        }
    }

    public function BidStore(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        $required = array('minBid', 'maxBid', 'bid', 'qty', 'userId', 'itemId');
        if(!$request->has($required)){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        $bidder = user::find($request->userId);
        $item = items::find($request->itemId);
        if($bidder == NULL or $item == NULL){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'invalid user or item, log again and bid please';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        $newBid = new bid();
        $newBid->user_id = $bidder->id;
        $newBid->max_bid = $request->maxBid;
        $newBid->bid = $request->bid;
        $newBid->qty = $request->qty;
        $newBid->item_id = $item->id;
        $newBid->date = (date("Y:m:d H:i:s"));

        //last checks
        $message = 'generic error';
        $err=0;
        if($request->qty > $item->quantity){
            $message = 'you are asking for more quantity we can sell';
            $err=1;
        }
        if($request->bid < $request->minBid){
            $err=1;
            $message = 'your bid is minus than the minimum bid';
        }
        if($request->maxBid < $request->minBid){
            $err=1;
            $message = 'max bid min than min bid';
        }
        if($request->maxBid < $request->bid){
            $err=1;
            $message = 'max bid min than bid';
        }
        if($err==1){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]); //no bid stored!
        }
        else{
            $title = 'RUBiS: Bidding result';
            $item->max_bid = $request->maxBid;
            $item->nb_of_bids = $item->nb_of_bids +1;
            $newBid->save();
            $item->save();
            return view('bid.store', ['title' => $title, 'filename' => $filename]);
        }
    }
}
