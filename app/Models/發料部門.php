<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class 發料部門 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "發料部門";

    protected $primaryKey = '發料部門';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '發料部門',
    ];
}
