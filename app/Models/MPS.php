<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPS extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "MPS";

    protected $primaryKey = ['客戶別', '機種', '製程', '料號90'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '客戶別',
        '機種',
        '製程',
        '下月MPS',
        '下月生產天數',
        '本月MPS',
        '本月生產天數',
        '填寫時間',
        '料號90',
    ];
}
