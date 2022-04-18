<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ConsumptiveMaterial extends Model
{
    use HasFactory;
    // use SoftDeletes;

    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumptive_material';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = '料號';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    // protected $connection = 'sqlite';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '料號',
        '品名',
        '規格',
        '單價',
        '幣別',
        '單位',
        'MPQ',
        'MOQ',
        'LT',
        '月請購',
        'A級資材',
        '耗材歸屬',
        '發料部門',
        '安全庫存',
    ];

    /**
     * Get the 月請購＿單耗s for this 料號.
     */
    public function monthly_unit()
    {
        return $this->hasMany(月請購_單耗::class, '料號', '料號'); // Comment::class, 'foreign_key', 'local_key'
    } // monthly_unit

} // end of class
