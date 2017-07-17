<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::Group(['prefix' => '/PHP/'], function(){

    Route::get('',['as' => 'home', function () {
        return view('home');
    }]);

    Route::get('index.html', function() {
        return Redirect::route('home');
    });

    Route::get('register.html', function() {
        return view('register');
    });

    Route::get('browse.html', function() {
        return view('browse');
    });

    Route::get('sell.html', function() {
        return view('welcome.sell');
    });

    Route::get('about_me.html', function() {
        return view('welcome.aboutme');
    });

    Route::match(array('GET', 'POST'),'RegisterUser.php', 'addNewUserController@AddNewUser');
    //browsing routes
    Route::get('BrowseRegions.php', 'getRegionsController@all');
    Route::match(array('GET', 'POST'),'BrowseCategories.php', 'browseCategoriesController@SwitchUsersTo');
    //search routes
    Route::match(array('GET', 'POST'),'SearchItemsByRegion.php', 'searchItemsController@ByRegion');
    Route::match(array('GET', 'POST'),'SearchItemsByCategory.php', 'searchItemsController@ByCategory');
    Route::match(array('GET', 'POST'),'ViewItem.php', 'itemInfoController@itemInfoPage');
    Route::match(array('GET', 'POST'),'ViewBidHistory.php', 'itemInfoController@itemBidHistory');
    Route::match(array('GET', 'POST'),'ViewUserInfo.php', 'userInfoController@AboutSomeone');
    //comments
    Route::match(array('GET', 'POST'),'PutCommentAuth.php', 'commentsController@CommentAuth');
    Route::match(array('GET', 'POST'),'StoreComment.php', 'commentsController@CommentStore');

    //selling
    Route::match(array('GET', 'POST'),'SellItemForm.php', 'sellController@FormSeller');
    Route::match(array('GET', 'POST'),'RegisterItem.php', 'sellController@StoreSell');

    //bidding
    Route::match(array('GET', 'POST'),'PutBidAuth.php', 'bidsController@BidAuth');
    Route::match(array('GET', 'POST'),'StoreBid.php', 'bidsController@BidStore');

    //buynow
    Route::match(array('GET', 'POST'),'BuyNowAuth.php', 'buyNowController@BuyAuth');
    Route::match(array('GET', 'POST'),'StoreBuyNow.php', 'buyNowController@BuyStore');

    //all the route which wants credential
    Route::group(['middleware' => 'login'], function() {

        Route::match(array('GET', 'POST'),'PutComment.php', 'commentsController@CommentForm');
        Route::match(array('GET', 'POST'),'PutBid.php', 'bidsController@BidForm');
        Route::match(array('GET', 'POST'), 'AboutMe.php','userInfoController@AboutMe');
        Route::match(array('GET', 'POST'),'BuyNow.php', 'buyNowController@BuyForm');


    });

});

