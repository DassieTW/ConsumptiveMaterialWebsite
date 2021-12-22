<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 線別 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "線別";

    protected $primaryKey = '線別';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '線別',
    ];
}
