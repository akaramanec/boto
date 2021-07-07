<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OrderCountRule implements Rule
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function passes($attribute, $value)
    {
        if ($this->product->count >= $value) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Available only {$this->product->count} amount of {$this->product->name}";
    }
}
