<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'project_id',
        'chapter_id',
        'section_type_id',
        'progress_status',
        'title',
        'synopsis',
        'body',
        'sort_order',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(SectionType::class, 'section_type_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
