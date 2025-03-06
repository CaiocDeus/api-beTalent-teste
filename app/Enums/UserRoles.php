<?php

namespace App\Enums;

enum UserRoles: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Finance = 'finance';
    case User = 'user';
}
