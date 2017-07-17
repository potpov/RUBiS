<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\items
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
 * @method static \Illuminate\Database\Query\Builder|\App\items whereBuyNow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereInitialPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereMaxBid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereNbOfBids($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereReservePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereSeller($value)
 * @method static \Illuminate\Database\Query\Builder|\App\items whereStartDate($value)
 */
class items extends Model
{
    public $timestamps = FALSE;

}
