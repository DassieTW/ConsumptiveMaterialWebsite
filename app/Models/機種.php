<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 機種 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "機種";

    protected $primaryKey = '機種';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '機種',
    ];
}
