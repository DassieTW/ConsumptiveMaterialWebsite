<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 退回原因 extends Model
{

    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "退回原因";

    protected $primaryKey = '退回原因';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '退回原因',
    ];
}
