<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\id
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $category
 * @property int $region
 * @property int $users
 * @property int $item
 * @property int $comment
 * @property int $bid
 * @property int $buyNow
 * @method static \Illuminate\Database\Query\Builder|\App\id whereBid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereBuyNow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereItem($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereRegion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\id whereUsers($value)
 */
class id extends Model
{
    public $timestamps = FALSE;

}
