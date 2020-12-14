<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends model
{
    protected $connection;
    protected $garded = [];
    public function __constructor()
    {
        $this->connection = Auth::user()->db_name;
    }
    protected $table = "test";
}
