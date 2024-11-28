<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ImagenArrayValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar que cada archivo en el array cumple con los requisitos
        foreach ($value as $file) {
            // Validar que el archivo sea de un tipo permitido
            if (!in_array($file->getClientOriginalExtension(), ['jpeg', 'png', 'jpg', 'gif'])) {
                $fail("Las imagen debe ser de tipo jpeg, png, jpg o gif.");
                return;
            }

            // Validar que el archivo no exceda el tamaño máximo permitido (2 MB)
            if ($file->getSize() > 2048 * 1024) {
                $fail("Las imagen no puede exceder los 2MB.");
                return;
            }
        }
    }
}
