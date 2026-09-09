<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait HasTranslations
{
    /**
     * Get the translated value for a field.
     *
     * @param string $field
     * @param string|null $locale
     * @return string
     */
    public function getLocalized(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();
        $translations = $this->$field;

        if (is_string($translations)) {
            $translations = json_decode($translations, true) ?? [];
        }

        if (!is_array($translations)) {
            return (string)$translations;
        }

        // Return request locale, fallback to Hindi (hi), then first available, then empty string
        return $translations[$locale] ?? $translations['hi'] ?? array_values($translations)[0] ?? '';
    }

    /**
     * Set translation for a field.
     *
     * @param string $field
     * @param string $locale
     * @param string $value
     * @return $this
     */
    public function setTranslation(string $field, string $locale, string $value): self
    {
        $translations = $this->$field ?? [];
        
        if (is_string($translations)) {
            $translations = json_decode($translations, true) ?? [];
        }
        
        $translations[$locale] = $value;
        $this->$field = $translations;
        
        return $this;
    }
}
