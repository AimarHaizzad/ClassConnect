<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
<<<<<<< HEAD
              'title' => $this->faker->sentence(),
=======
            'title' => $this->faker->sentence(),
>>>>>>> origin/main
            'description' => $this->faker->paragraph(),
            'file_path' => $this->faker->filePath(),
            'subject_id' => \App\Models\Subject::factory(),
        ];
    }
}
