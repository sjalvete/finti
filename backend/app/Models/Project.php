<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = ['name', 'description'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['role'])->withTimestamps();
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }
}
