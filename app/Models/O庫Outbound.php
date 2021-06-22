<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Outbound extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "O庫Outbound";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '品名',
        '規格',
        '廠別',
        '庫別',
        '數量',
        '領料人',
        '時間',
        '備註',
    ];
}
