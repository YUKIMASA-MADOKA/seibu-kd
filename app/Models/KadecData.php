<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KadecData extends Model
{
    use HasFactory;
    protected $table = "trn_kadec_data";
    protected $primaryKey = ['day','hms'];

    public $timestamps = false;
    public $incrementing = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
