<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VpilData extends Model
{
    use HasFactory;
    protected $table = "view_pi_latest";

    public $timestamps = false;
    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
