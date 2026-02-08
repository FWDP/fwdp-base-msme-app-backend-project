<?php

namespace App\Core\Auth;

enum Roles
{
    const string ADMIN = 'ADMIN';
    const string MODERATOR = 'MODERATOR';
    const string MSME_USER = 'MSME_USER';
}
