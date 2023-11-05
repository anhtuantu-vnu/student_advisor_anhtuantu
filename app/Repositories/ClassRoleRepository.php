<?php

namespace App\Repositories;

use App\Models\ClassRole;

class ClassRoleRepository extends AbstractRepository
{
    public function __construct(ClassRole $classRole)
    {
        parent::__construct($classRole);
    }


}
