<?php
namespace System\Core;

use System\Database\DB;

abstract class Model
{
    protected $table;
    protected $primaryKey = 'id';

    public function all()
    {
        return DB::table($this->table)->get();
    }

    public function find($id)
    {
        return DB::table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->first();
    }

    public function create(array $data)
    {
        return DB::table($this->table)->insert($data);
    }

    public function update($id, array $data)
    {
        return DB::table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return DB::table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->delete();
    }
}
