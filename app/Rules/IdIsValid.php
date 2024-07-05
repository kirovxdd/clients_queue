<?php

namespace App\Rules;

use App\Service\ClientService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IdIsValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $clientService = new ClientService();

        if (!$clientService->isClientValid($value)) {
            $fail("there is no client with $attribute: $value, you should create a new client");
        }
    }
}
