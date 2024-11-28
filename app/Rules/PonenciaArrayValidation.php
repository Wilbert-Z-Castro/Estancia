<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PonenciaArrayValidation implements ValidationRule
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
        // Tipos de archivos permitidos: PDF, PowerPoint y formatos de imagen
        $allowedExtensions = ['pdf', 'ppt', 'pptx', 'jpeg', 'png', 'jpg', 'gif'];
        
        foreach ($value as $file) {
            // Validar que el archivo sea de un tipo permitido
            if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                $fail("Cada archivo de ponencia debe ser de tipo PDF, PowerPoint o un formato de imagen (jpeg, png, jpg, gif).");
                return;
            }

            // Validar que el archivo no exceda el tamaño máximo permitido (5 MB)
            if ($file->getSize() > 5 * 1024 * 1024) { // 5 MB en bytes
                $fail("Cada archivo de ponencia no puede exceder los 5MB.");
                return;
            }
        }
    }
}
