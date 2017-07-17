<?php

namespace App\Http\Controllers;

use App\comment;
use App\items;
use App\user;
use Illuminate\Http\Request;
use \DB;

class userInfoController extends Controller
{
    public function AboutSomeone(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if($request->has('userId') and user::where('id', $request->userId)->count() ==1){
            $user = user::where('id', $request->userId)->first();
            $comments = DB::table(DB::raw('comments, users'))
                ->select(DB::raw('comments.*, users.nickname as sellerUser'))
                ->whereRaw('comments.from_user_id=users.id')
                ->whereRaw('to_user_id='. $request->userId)
                ->get();
            $title = 'RUBiS: View user information';
            return view('other_user_information', [
                'user' => $user,
                'comments' => $comments,
                'filename' => $filename,
                'title' => $title
            ]);
        } else {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
    }


    public function AboutMe(Request $request){
        $user = $request->get('nickname');
        $password = $request->get('password');
        //general user informations
        $userInfo = user::select('id', 'firstname', 'lastname', 'password',
                                            'email', 'nickname', 'creation_date', 'rating')
                    ->where('nickname', $user)
                    ->where('password', $password)
                    ->first();

        $title = 'RUBiS: About me';
        $filename = strtok(basename($request->getUri()), "?");

        $userID = $userInfo->id;
        $today = date('Y-m-d H:i:s');

        //items where the user bid on
        //!!!there are 2 max bid: item max bid and bid max bid which is also the user max bid!!!
        $itemYouBid = DB::table(DB::raw('bids as bids1, items, users'))
            ->select(DB::raw( 'items.*, bids1.max_bid as userMaxBid, users.nickname as sellerUser' ))
            ->where(function ($query) use ($userID) {
                        $query->whereRaw( 'bids1.user_id  = ' . $userID);
            })
            ->whereRaw('bids1.item_id = items.id')
            ->whereRaw('users.id = items.seller')
            ->where(function ($query) use ($today) {
                $query->whereRaw('items.end_date >= "' . $today . '"');
            })
            ->where('bids1.max_bid', '=', function($query) {
                $query->select(\DB::raw('MAX(bids2.max_bid)'))
                    ->from('bids as bids2')
                    ->whereRaw('bids1.item_id = bids2.item_id')
                    ->whereRaw('bids1.user_id = bids2.user_id');
            })
            ->get();

        //items you won
        $itemYouWon = DB::table(DB::raw('bids, items, users'))
                        ->select(DB::raw('items.*, users.nickname as sellerUser'))
                        ->groupBy('item_id')
                        ->whereRaw('bids.user_id='.$userID)
                        ->whereRaw('bids.item_id=items.id')
                        ->whereRaw('TO_DAYS(NOW()) - TO_DAYS(items.end_date) < 30')
                        ->whereRaw('users.id = items.seller')
                        ->get();

        //items you bought
        $itemYouBought = DB::table(DB::raw('items, buy_now, users'))
                        ->select(DB::raw('items.id, items.name, items.buy_now, buy_now.qty AS quantity, items.seller, users.nickname as sellerUser'))
                        ->whereRaw('items.id=buy_now.item_id')
                        ->whereRaw('buy_now.buyer_id='.$userID)
                        ->whereRaw('TO_DAYS(NOW()) - TO_DAYS(buy_now.date)<=30')
                        ->whereRaw('users.id = items.seller')
                        ->get();

        //item youre selling
        $itemYouSelling = items::where('seller', $userID)
                                ->whereDate('end_date', '>=',$today)
                                ->get();

        //item you sold
        $itemYouSold = items::where('seller', $userID)
            ->whereRaw('TO_DAYS(NOW()) - TO_DAYS(items.end_date) < 30')
            ->get();

        //comments
        $comment = DB::table(DB::raw('comments, users'))
                        ->select(DB::raw('comments.*, users.nickname as FromUser'))
                        ->where('to_user_id', $userID)
                        ->whereRaw('from_user_id=users.id')
                        ->get();
        //$comment = comment::where('to_user_id', $userID)->get();

        return view('user_information', ['userInfo' => $userInfo,
                                            'title' => $title,
                                            'filename' => $filename,
                                            'itemYouBid' => $itemYouBid,
                                            'itemYouWon' => $itemYouWon,
                                            'itemYouBought' => $itemYouBought,
                                            'itemYouSelling' => $itemYouSelling,
                                            'itemYouSold'   => $itemYouSold,
                                            'comment'       => $comment
        ]);

    }
}
