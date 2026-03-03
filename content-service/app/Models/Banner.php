<?php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Banner extends Model
{
    use HasFactory, HasUuids; 

    protected $guarded = [];
}
