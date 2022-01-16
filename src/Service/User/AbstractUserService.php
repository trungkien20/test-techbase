<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Exception\UserException;
use App\Repository\UserRepository;
use App\Service\AbstractService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

/**
 * Class AbstractUserService
 * @package App\Service\User
 */
abstract class AbstractUserService extends AbstractService
{
    private const REDIS_KEY = 'user:%s';

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var RedisService
     */
    protected RedisService $redisService;

    /**
     * @param UserRepository $userRepository
     * @param RedisService $redisService
     */
    public function __construct(UserRepository $userRepository, RedisService $redisService)
    {
        $this->userRepository = $userRepository;
        $this->redisService = $redisService;
    }

    /**
     * @param string $name
     * @return string
     * @throws UserException
     */
    protected static function validateUserName(string $name): string
    {
        if (!v::alnum('ÁÉÍÓÚÑáéíóúñ.')->length(1, 200)->validate($name)) {
            throw new UserException('Invalid name.', 400);
        }

        return $name;
    }

    /**
     * @param string $emailValue
     * @return string
     * @throws UserException
     */
    protected static function validateEmail(string $emailValue): string
    {
        $email = filter_var($emailValue, FILTER_SANITIZE_EMAIL);
        if (!v::email()->validate($email)) {
            throw new UserException('Invalid email', 400);
        }

        return (string)$email;
    }

    /**
     * @param string $phone
     * @return string
     * @throws UserException
     */
    protected static function validatePhone(string $phone): string
    {
        if (!v::phone()->length(1, 64)->validate($phone)) {
            throw new UserException('Invalid phone', 400);
        }

        return $phone;
    }

    /**
     * @param string $address
     * @return string
     * @throws UserException
     */
    protected static function validateUserAddress(string $address): string
    {
        if (!v::alnum('ÁÉÍÓÚÑáéíóúñ.')->length(1, 255)->validate($address)) {
            throw new UserException('Invalid address.', 400);
        }

        return $address;
    }

    /**
     * @param int $userId
     * @return object
     * @throws UserException
     */
    protected function getUserFromCache(int $userId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $userId);
        $key = $this->redisService->generateKey($redisKey);

        if ($this->redisService->exists($key)) {
            $data = $this->redisService->get($key);
            $user = json_decode((string)json_encode($data), false);
        } else {
            $user = $this->getUserFromDb($userId)->toJson();
            $this->redisService->setex($key, $user);
        }


        return $user;
    }

    /**
     * @param int $userId
     * @return User
     * @throws UserException
     */
    protected function getUserFromDb(int $userId): User
    {
        return $this->userRepository->getUser($userId);
    }

    /**
     * @param int $id
     * @param object $user
     */
    protected function saveInCache(int $id, object $user): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $id);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $user);
    }
}
