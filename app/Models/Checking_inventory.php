<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Checking_inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checking_inventory';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    // protected $primaryKey = ['id','單號'];
    // laravel model doesn't support Composite Primary Keys

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

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
        '單號',
        '料號',
        '現有庫存',
        '儲位',
        '客戶別',
        '盤點',
    ];

}
