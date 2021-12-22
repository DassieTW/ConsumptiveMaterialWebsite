<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 調撥單 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "調撥單";

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '品名',
        '規格',
        '單位',
        '庫存',
        '調撥數量',
        '調撥人',
        '接收人',
        '撥出廠區',
        '接收廠區',
        '撥出數量',
        '接收數量',
        '調撥單號',
        '開單時間',
        '出庫時間',
        '入庫時間',
        '狀態',
    ];
}
