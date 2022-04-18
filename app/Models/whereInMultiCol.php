<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class whereInMultiCol extends Model
{
    use HasFactory;
    protected $fillable = ['morphable_type', 'morphable_id'];

    /**
     * @param  array  $columns
     * @param  array  $values
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeWhereInMultiple(Builder $query, array $columns, array $values)
    {
        collect($values)
            ->transform(function ($v) use ($columns) {
                $clause = [];
                foreach ($columns as $index => $column) {
                    $clause[] = [$column, '=', $v[$index]];
                }
                return $clause;
            })
            ->each(function($clause, $index) use ($query) {
                $query->where($clause, null, null, $index === 0 ? 'and' : 'or');
            });

        return $query;
    } // scopeWhereInMultiple
} // end of class