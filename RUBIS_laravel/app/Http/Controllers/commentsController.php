<?php

namespace App\Http\Controllers;

use App\comment;
use App\items;
use App\user;
use Illuminate\Http\Request;

class commentsController extends Controller
{
    public function CommentAuth(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        if(!$request->has('itemId') or !$request->has('to')){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if (user::where('id', $request->to)->count() != 1 or items::where('id', $request->itemId)->count() != 1){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'invalid parameters';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        $title = 'RUBiS: User authentification for comment';
        return view('comments.auth', [
            'ToUser' => $request->to,
            'itemID' => $request->itemId,
            'filename' => $filename,
            'title' => $title
        ]);
    }

    public function CommentForm(Request $request){
        //auth thought middleware
        $filename = strtok(basename($request->getUri()), "?");
        $required = array('nickname', 'password', 'itemId', 'to');
        if(!$request->has($required)){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if(user::where('id', $request->to)->count()!=1 or items::where('id', $request->itemId)->count()!=1) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }

        $fromUser = user::where('nickname', $request->nickname)->where('password', $request->password)->first();
        $toUser = user::where('id', $request->to)->first();
        $item = items::where('id', $request->itemId)->first();
        $title = 'RUBiS: Comment service';
        return view('comments.form', [
            'ToUser' => $toUser,
            'FromUser' => $fromUser,
            'item' => $item,
            'filename' => $filename,
            'title' => $title
        ]);

    }


    public function CommentStore(Request $request){
        $filename = strtok(basename($request->getUri()), "?");
        $required = array('itemId', 'from', 'to', 'rating', 'comment');
        if(!$request->has($required)){
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters missed';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }
        if(user::where('id', $request->to)->count()!=1 or items::where('id', $request->itemId)->count()!=1 or user::where('id', $request->from)->count()!=1) {
            $title = 'RUBiS ERROR: "' . strtok(basename($request->getUri()), "?") . '"';
            $message = 'required parameters invalid';
            return view('error', ['title' => $title, 'filename' => $filename, 'message' => $message]);
        }

        $comment = new comment();
        $comment->from_user_id = $request->from;
        $comment->to_user_id = $request->to;
        $comment->rating = $request->rating;
        $comment->item_id = $request->itemId;
        $comment->date = date("Y:m:d H:i:s");
        $comment->comment = $request->comment;
        $ToUser = user::where('id', $request->to)->first();
        $ToUser->rating = $ToUser->rating + $request->rating;
        $comment->save();
        $ToUser->save();

        $title = 'RUBiS: Comment posting';
        return view('comments.store', [
            'filename' => $filename,
            'title' => $title
        ]);
    }
}
