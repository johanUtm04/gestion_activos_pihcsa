<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inpc extends Model
{
    use HasFactory;
    protected $table = 'inpc_indices';
    protected $guarded = ['id'];

}