<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\buy_now
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $buyer_id
 * @property int $item_id
 * @property int $qty
 * @property string $date
 * @method static \Illuminate\Database\Query\Builder|\App\buy_now whereBuyerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\buy_now whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\buy_now whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\buy_now whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\buy_now whereQty($value)
 */
class buy_now extends Model
{
    protected $table = "buy_now";
    public $timestamps = FALSE;
}
