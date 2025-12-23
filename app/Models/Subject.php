<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    /** @use HasFactory<\Database\Factories\SubjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }
<<<<<<< HEAD
        public function lessons(): HasMany
=======
    public function lessons(): HasMany
>>>>>>> origin/main
    {
        return $this->hasMany(Lesson::class);
    }
}
