<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
    class Lesson extends Model
    {
        /** @use HasFactory<\Database\Factories\LessonFactory> */
        use HasFactory;
      protected $fillable = [
=======
class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonFactory> */
    use HasFactory;
    protected $fillable = [
>>>>>>> origin/main
        'title',
        'description',
        'file_path',
        'subject_id',
    ];

    public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
<<<<<<< HEAD
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class);
    }
=======
>>>>>>> origin/main
}
