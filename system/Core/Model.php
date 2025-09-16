<?php
namespace System\Core;

use System\Database\DB;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';

    // --- RELATIONSHIPS ---

    // hasOne
    public function hasOne(string $related, string $foreignKey, string $localKey = 'id')
    {
        $instance = new $related;
        return DB::table($instance->table)
            ->where($foreignKey, '=', $this->{$localKey})
            ->first();
    }

    // hasMany
    public function hasMany(string $related, string $foreignKey, string $localKey = 'id')
    {
        $instance = new $related;
        return DB::table($instance->table)
            ->where($foreignKey, '=', $this->{$localKey})
            ->get();
    }

    // belongsTo
    public function belongsTo(string $related, string $foreignKey, string $ownerKey = 'id')
    {
        $instance = new $related;
        return DB::table($instance->table)
            ->where($ownerKey, '=', $this->{$foreignKey})
            ->first();
    }

    // belongsToMany (pivot table)
    public function belongsToMany(
        string $related,
        string $pivotTable,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $localKey = 'id',
        string $relatedKey = 'id'
    ) {
        $instance = new $related;

        return DB::table($instance->table)
            ->join($pivotTable, "{$instance->table}.{$relatedKey}", '=', "{$pivotTable}.{$relatedPivotKey}")
            ->where("{$pivotTable}.{$foreignPivotKey}", '=', $this->{$localKey})
            ->get();
    }

    // hasManyThrough
    public function hasManyThrough(
        string $related,
        string $through,
        string $firstKey,
        string $secondKey,
        string $localKey = 'id',
        string $secondLocalKey = 'id'
    ) {
        $throughInstance = new $through;
        $relatedInstance = new $related;

        return DB::table($relatedInstance->table)
            ->join($throughInstance->table, "{$throughInstance->table}.{$secondLocalKey}", '=', "{$relatedInstance->table}.{$secondKey}")
            ->where("{$throughInstance->table}.{$firstKey}", '=', $this->{$localKey})
            ->get();
    }

    // morphOne
    public function morphOne(string $related, string $morphType, string $morphId, string $localKey = 'id')
    {
        $instance = new $related;
        return DB::table($instance->table)
            ->where($morphType, '=', static::class)
            ->where($morphId, '=', $this->{$localKey})
            ->first();
    }

    // morphMany
    public function morphMany(string $related, string $morphType, string $morphId, string $localKey = 'id')
    {
        $instance = new $related;
        return DB::table($instance->table)
            ->where($morphType, '=', static::class)
            ->where($morphId, '=', $this->{$localKey})
            ->get();
    }

    // morphToMany
    public function morphToMany(
        string $related,
        string $pivotTable,
        string $morphType,
        string $morphId,
        string $relatedKey,
        string $localKey = 'id'
    ) {
        $instance = new $related;

        return DB::table($instance->table)
            ->join($pivotTable, "{$instance->table}.{$relatedKey}", '=', "{$pivotTable}.{$relatedKey}")
            ->where("{$pivotTable}.{$morphType}", '=', static::class)
            ->where("{$pivotTable}.{$morphId}", '=', $this->{$localKey})
            ->get();
    }
}
