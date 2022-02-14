<?php

namespace App\Enums\Socilite;

use BenSampo\Enum\Enum;

final class VendorType extends Enum
{
    /**
     * Vendor type is google
     * 
     * @var int
     */
    const Google = 1;
    
    /**
     * Vendor type is facebook
     * 
     * @var int
     */
    const Facebook = 2;

    /**
     * Vendor type is github
     * 
     * @var int
     */
    const Github = 3;
}
