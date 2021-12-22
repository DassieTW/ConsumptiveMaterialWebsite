<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 人員信息 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = "人員信息";

    protected $primaryKey = '工號';

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '工號',
        '姓名',
        '部門',
    ];
}
