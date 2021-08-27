<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "inventory";

    protected $primaryKey = ['料號','儲位' , '客戶別'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '現有庫存',
        '儲位',
        '客戶別',
        '最後更新時間',
    ];
}
