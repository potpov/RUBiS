<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\user
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $nickname
 * @property string $password
 * @property string $email
 * @property int $rating
 * @property float $balance
 * @property string $creation_date
 * @property int $region
 * @method static \Illuminate\Database\Query\Builder|\App\user whereBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereCreationDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereFirstname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereLastname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\user whereRegion($value)
 */
class user extends Model
{
    public $timestamps = FALSE;

}
