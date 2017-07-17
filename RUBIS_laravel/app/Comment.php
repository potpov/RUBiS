<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\comment
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $from_user_id
 * @property int $to_user_id
 * @property int $item_id
 * @property int $rating
 * @property string $date
 * @property string $comment
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereFromUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\comment whereToUserId($value)
 */
class comment extends Model
{
    public $timestamps = FALSE;

}
