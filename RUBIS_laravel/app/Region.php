<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\region
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\region whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\region whereName($value)
 */
class region extends Model
{
    public $timestamps = FALSE;

}
