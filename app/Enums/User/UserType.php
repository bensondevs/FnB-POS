<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserType extends Enum
{
    /**
     * User type is the owner of a company
     * 
     * @var array
     */
    const Owner = 1;

    /**
     * User type is the employe of a company
     * 
     * @var array
     */
    const Employee = 2;

    /**
     * User type is the member of a company
     * 
     * @var array
     */
    const Member = 3;
}
