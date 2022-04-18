<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class 月請購_單耗 extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    protected $table = "月請購_單耗";

    protected $primaryKey = ['料號', '客戶別', '機種', '製程'];

    protected $keyType = 'string';

    //PK no return 0
    public $incrementing = false;

    protected $fillable = [
        '料號',
        '客戶別',
        '機種',
        '製程',
        '單耗',
        '狀態',
        '畫押工號',
        '畫押信箱',
        '畫押時間',
        '紀錄',
        '送單時間',
        '送單人',
    ];

    /**
     * Get the material info that has the same PN.
     */
    public function materials()
    {
        // Post::class, 'foreign_key', 'owner_key'
        return $this->belongsTo(ConsumptiveMaterial::class, '料號', '料號');
    }
}
