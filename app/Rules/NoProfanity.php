<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoProfanity implements ValidationRule
{
    /**
     * List of inappropriate words to filter
     */
    private array $bannedWords = [
        'fuck', 'fucking', 'fucked',
        'shit', 'shitting', 'shitted',
        'damn', 'damned',
        'ass', 'asshole',
        'bitch', 'bitches',
        'bastard',
        'crap',
        'hell',
        'idiot', 'stupid',
        'hate', 'hateful',
        'kill', 'killing', 'killed',
        'die', 'dying', 'death',
        'violence', 'violent',
        'attack', 'attacking',
        'hurt', 'hurting',
        'fight', 'fighting',
        'weapon', 'weapons',
        'gun', 'guns',
        'knife', 'knives',
        'bomb', 'bombs',
        'terror', 'terrorist',
        'murder', 'murdering',
        'suicide', 'suicidal',
        // Add more words as needed
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $lowercaseValue = strtolower($value);
        $words = preg_split('/\s+/', $lowercaseValue);

        foreach ($this->bannedWords as $bannedWord) {
            // Check for exact word matches and partial matches
            foreach ($words as $word) {
                // Remove punctuation for comparison
                $cleanWord = preg_replace('/[^a-z0-9]/', '', $word);

                if ($cleanWord === $bannedWord || str_contains($cleanWord, $bannedWord)) {
                    $fail('The :attribute contains inappropriate language. Please use respectful language in your posts.');

                    return;
                }
            }

            // Also check if the banned word appears anywhere in the text
            if (str_contains($lowercaseValue, $bannedWord)) {
                $fail('The :attribute contains inappropriate language. Please use respectful language in your posts.');

                return;
            }
        }
    }
}
