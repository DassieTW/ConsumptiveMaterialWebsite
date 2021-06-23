<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 客戶別 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "客戶別";

    protected $primaryKey = '客戶別';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '客戶',
    ];
}
