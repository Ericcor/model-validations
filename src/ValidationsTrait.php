<?php

namespace ModelValidations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Trait para validar modelos antes de crearlos o actualizarlos
 */
trait ValidationsTrait
{
    public static function bootValidationsTrait()
    {
        static::creating(function (Model $model) {
            $model->validate();
        });

        static::updating(function (Model $model) {
            $model->validate();
        });
    }

    public function validate()
    {
        $rules = $this->getValidations();

        if (empty($rules)) {
            return true; // Evita fallos si no hay reglas de validación
        }

        // Validar los atributos del modelo con las reglas definidas
        Validator::make($this->getAttributes(), $rules)->validate();

        return true;
    }

    public function getValidations(): array
    {
        if (!$this->exists && method_exists($this, 'getCreateValidations')) {
            return $this->getCreateValidations();
        }

        if (method_exists($this, 'getUpdateValidations')) {
            return $this->getUpdateValidations();
        }

        return property_exists($this, 'validations') ? $this->validations : [];
    }
}
