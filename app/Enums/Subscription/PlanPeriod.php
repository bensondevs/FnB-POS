<?php

namespace App\Enums\Subscription;

use BenSampo\Enum\Enum;

final class PlanPeriod extends Enum
{
    /**
     * Plan period is in days
     * 
     * @var int
     */
    const Days = 1;

    /**
     * Plan period is in months
     * 
     * @var int
     */
    const Months = 2;

    /**
     * Plan period is in year
     * 
     * @var int
     */
    const Years = 3;
}
