<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocTransferRecord extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "LocTransferRecord";

    public $incrementing = false;

    protected $fillable = [
        '料號',
        '調動數量',
        '操作人',
        '調出儲位',
        '原調出儲位庫存',
        '接收儲位',
        '原接收儲位庫存',
        '操作時間',
    ];
}
