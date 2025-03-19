<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ModelActivityObserver;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(ModelActivityObserver::class);
    }
}
