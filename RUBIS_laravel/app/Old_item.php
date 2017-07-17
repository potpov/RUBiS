<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\old_item
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $initial_price
 * @property int $quantity
 * @property float $reserve_price
 * @property float $buy_now
 * @property int $nb_of_bids
 * @property float $max_bid
 * @property string $start_date
 * @property string $end_date
 * @property int $seller
 * @property int $category
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereBuyNow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereInitialPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereMaxBid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereNbOfBids($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereReservePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereSeller($value)
 * @method static \Illuminate\Database\Query\Builder|\App\old_item whereStartDate($value)
 */
class old_item extends Model
{
    public $timestamps = FALSE;

}
