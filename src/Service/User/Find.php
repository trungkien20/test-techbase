<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Exception\UserException;

/**
 * Class Find
 * @package App\Service\User
 */
class Find extends AbstractUserService
{
    /**
     * @param int $page
     * @param int $perPage
     * @param string|null $name
     * @param string|null $email
     * @return array
     */
    public function getUsersByPage(int $page,int $perPage, ?string $name, ?string $email): array
    {
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->userRepository->getUsersByPage(
            $page,
            $perPage,
            $name,
            $email
        );
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    /**
     * @param int $userId
     * @return object
     * @throws UserException
     */
    public function getOne(int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $user = $this->getUserFromCache($userId);
        } else {
            $user = $this->getUserFromDb($userId)->toJson();
        }

        return $user;
    }
}
