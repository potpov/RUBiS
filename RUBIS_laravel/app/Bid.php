<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\bid
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $qty
 * @property float $bid
 * @property float $max_bid
 * @property string $date
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereBid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereMaxBid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereQty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\bid whereUserId($value)
 */
class bid extends Model
{
    public $timestamps = FALSE;

}
