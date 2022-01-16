<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\UserException;

/**
 * Class Create
 * @package App\Service\User
 */
class Create extends AbstractUserService
{
    /**
     * @param array $params
     * @return object
     * @throws UserException
     */
    public function create(array $params): object
    {
        $data = $this->validateAndGetUserData($params);
        $user = $this->userRepository->create($data);

        if (self::isRedisEnabled() === true) {
            $this->saveInCache($user->getId(), $user->toJson());
        }

        return $user->toJson();
    }

    /**
     * @param array $params
     * @return User
     * @throws UserException
     */
    private function validateAndGetUserData(array $params): User
    {
        $user = json_decode((string) json_encode($params), false);

        $myUser = new User();
        $myUser->updateName(self::validateUserName($user->name));
        $myUser->updateEmail(self::validateEmail($user->email));
        $myUser->updatePassword(hash('sha512', $user->password));

        $this->userRepository->checkUserByEmail($user->email);

        return $myUser;
    }
}
