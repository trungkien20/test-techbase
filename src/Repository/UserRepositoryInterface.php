<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

/**
 * Interface UserRepositoryInterface
 * @package App\Repository
 */
interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public function loginUser(string $email, string $password): User;
}
