<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\UserException;

/**
 * Class Update
 * @package App\Service\User
 */
class Update extends AbstractUserService
{
    /**
     * @param array $input
     * @param User $user
     * @return object
     * @throws UserException
     */
    public function update(array $input, User $user): object
    {
        $data = $this->validateUserData($input, $user);

        $userUpdated = $this->userRepository->update($data);

        if (self::isRedisEnabled() === true) {
            $this->saveInCache($userUpdated->getId(), $userUpdated->toJson());
        }

        return $userUpdated->toJson();
    }

    /**
     * @param array $params
     * @param User $user
     * @return User
     * @throws UserException
     */
    private function validateUserData(array $params, User $user): User
    {
        $data = json_decode((string) json_encode($params), false);

        if (!isset($data->name) && !isset($data->phone) && !isset($data->address)) {
            throw new UserException('Enter the data to update the user.', 400);
        }

        if (isset($data->name)) {
            $user->updateName(self::validateUserName($data->name));
        }

        if (isset($data->phone)) {
            $user->updatePhone(self::validatePhone($data->phone));
        }

        if (isset($data->address)) {
            $user->updateAddress(self::validateUserAddress($data->address));
        }

        return $user;
    }
}
