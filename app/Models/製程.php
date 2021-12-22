<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 製程 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "製程";

    protected $primaryKey = '制程';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '制程',
    ];
}
