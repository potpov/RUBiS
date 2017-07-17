<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\category
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\category whereName($value)
 */
class category extends Model
{
    public $timestamps = FALSE;

}
