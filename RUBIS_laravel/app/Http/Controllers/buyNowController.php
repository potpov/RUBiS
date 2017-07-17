<?php

namespace App\Http\Controllers;

use App\buy_now;
use App\items;
use App\user;
use Illuminate\Http\Request;

class buyNowController extends Controller
{
    public function BuyAuth(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('itemId') or items::find($request->itemId) == NULL) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        } else {
            $title='RUBiS: User authentification for buying an item';
            return view('buynow.auth', ['title' => $title, 'filename' => $filename, 'itemID' => $request->itemId]);
        }
    }

    public function BuyForm(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('itemId') or items::find($request->itemId) == NULL) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'item invalid';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        $item = items::find($request->itemId);
        $buyer = user::where('nickname', $request->nickname)->where('password', $request->password)->first();
        $seller = user::find($item->seller);
        return view('buynow.form', [
           'item' => $item,
            'buyer' => $buyer,
            'seller' => $seller,
            'title' => 'RUBiS: Buy Now',
            'filename' => $filename
        ]);
    }

    public function BuyStore(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        $required = array('userId', 'itemId', 'qty', 'maxQty');
        if(!$request->has($required)){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }

        $item = items::find($request->itemId);
        $buyer = user::find($request->userId);
        if($buyer == NULL or $item == NULL) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'invalid user or item';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if($request->qty > $request->maxQty) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'you want more than we can sell you.';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }


        //okay...updating item
        $item->quantity = $item->quantity - $request->qty;
        if($item->quantity == 0)
            $item->end_date = date('Y-m-d H:i:s');
        //creating buynow row
        $buynow = new buy_now();
        $buynow->date = date("Y:m:d H:i:s");
        $buynow->buyer_id = $buyer->id;
        $buynow->item_id = $item->id;
        $buynow->qty = $request->qty;

        $buynow->save();
        $item->save();

        $title = 'RUBiS: BuyNow result';
        return view('buynow.store', [
            'title' => $title,
            'filename' => $filename,
            'qty' => $request->qty
        ]);
    }
}
