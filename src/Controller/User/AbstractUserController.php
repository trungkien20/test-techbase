<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Exception\UserException;
use App\Service\User\Create;
use App\Service\User\Find;
use App\Service\User\Login;
use App\Service\User\Update;

/**
 * Class AbstractUserController
 * @package App\Controller\User
 */
abstract class AbstractUserController extends BaseController
{
    /**
     * @return Login
     */
    protected function getLoginUserService(): Login
    {
        return $this->container->get('login_user_service');
    }

    /**
     * @return Find
     */
    protected function getFindUserService(): Find
    {
        return $this->container->get('find_user_service');
    }

    /**
     * @return Create
     */
    protected function getCreateUserService(): Create
    {
        return $this->container->get('create_user_service');
    }

    /**
     * @return Update
     */
    protected function getUpdateUserService(): Update
    {
        return $this->container->get('update_user_service');
    }

    /**
     * @param int $userId
     * @param int $userIdLogged
     * @throws UserException
     */
    protected function checkUserPermissions(int $userId, int $userIdLogged): void
    {
        if ($userId !== $userIdLogged) {
            throw new UserException('User permission failed.', 400);
        }
    }

    /**
     * @param array $input
     * @return int
     * @throws UserException
     */
    protected function getAndValidateUserId(array $input): int
    {
        if (isset($input['decoded']) && isset($input['decoded']->sub)) {
            return (int) $input['decoded']->sub;
        }

        throw new UserException('Invalid user. Permission failed.', 400);
    }
}
