<?php

declare(strict_types=1);

namespace App\Exceptions\Purchases;

use Exception;

final class InvalidPurchaseApproval extends Exception
{
    public function __construct(string $message = 'Invalid purchase approval.')
    {
        parent::__construct($message);
    }
}
