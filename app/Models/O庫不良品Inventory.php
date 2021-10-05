<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class O庫不良品Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "O庫不良品inventory";

    protected $primaryKey = ['料號' , '客戶別','庫別'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '現有庫存',
        '客戶別',
        '庫別',
        '最後更新時間',
        '品名',
        '規格',
    ];
}
