<?php

namespace ModelValidations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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
            return;
        }

        Validator::make($this->getAttributes(), $rules)->validate();
    }

    public function getValidations(): array
    {
        if (!$this->exists && method_exists($this, 'getCreateValidations')) {
            return $this->getCreateValidations();
        }

        if (method_exists($this, 'getUpdateValidations')) {
            return $this->getUpdateValidations();
        }

        return $this->validations ?? [];
    }
}