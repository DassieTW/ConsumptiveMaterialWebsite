<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫Inbound extends Model
{
    use HasFactory;
    //use SoftDeletes;

    protected $table = "O庫inbound";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '入庫單號',
        '料號',
        '品名',
        '規格',
        '客戶別',
        '庫別',
        '數量',
        '入庫人員',
        '時間',
        '備註',
        '入庫原因',
    ];
}
