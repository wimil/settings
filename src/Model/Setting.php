<?php

namespace Wimil\Settings\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $fillable = ['key', 'value'];
}
