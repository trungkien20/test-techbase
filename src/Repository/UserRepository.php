<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserException;

/**
 * Class UserRepository
 * @package App\Entity\User
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param \PDO $database
     * @param User $user
     */
    public function __construct(\PDO $database, User $user)
    {
        parent::__construct($database);

        $this->user = $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserException
     */
    public function loginUser(string $email, string $password): User
    {
        $query = '
            SELECT *
            FROM `users`
            WHERE `email` = :email AND `password` = :password
        ';

        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->bindParam('password', $password);

        $statement->execute();

        $user = $statement->fetch();

        if (!$user) {
            throw new UserException('Login failed: Email or password incorrect.', 400);
        }

        $userModel = new User();
        $userModel->setId($user['id']);

        return $userModel;
    }

    /**
     * @param int $userId
     * @return User
     * @throws UserException
     */
    public function getUser(int $userId): User
    {
        $query = 'SELECT `id`, `name`, `email`, `address`, `phone` FROM `users` WHERE `id` = :id';

        $statement = $this->database->prepare($query);
        $statement->bindParam('id', $userId);
        $statement->execute();
        $row = $statement->fetch();

        if (!$row) {
            throw new UserException('User not found.', 404);
        }

        return $this->user->transferToObject($row);
    }

    /**
     * @param string $email
     * @throws UserException
     */
    public function checkUserByEmail(string $email): void
    {
        $query = 'SELECT * FROM `users` WHERE `email` = :email';

        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->execute();
        $user = $statement->fetchObject();

        if ($user) {
            throw new UserException('Email already exists.', 400);
        }
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param string|null $name
     * @param string|null $email
     * @return array
     */
    public function getUsersByPage(
        int $page,
        int $perPage,
        ?string $name,
        ?string $email
    ): array {
        $params = [
            'name' => is_null($name) ? '' : $name,
            'email' => is_null($email) ? '' : $email,
        ];
        $query = $this->getQueryUsersByPage();
        $statement = $this->database->prepare($query);

        $statement->bindParam('name', $params['name']);
        $statement->bindParam('email', $params['email']);

        $statement->execute();
        $total = $statement->rowCount();

        return $this->getResultsWithPagination(
            $query,
            $page,
            $perPage,
            $params,
            $total
        );
    }

    /**
     * @return string
     */
    public function getQueryUsersByPage(): string
    {
        return "
            SELECT `id`, `name`, `email`, `address`,`phone`
            FROM `users`
            WHERE `name` LIKE CONCAT('%', :name, '%')
            AND `email` LIKE CONCAT('%', :email, '%')
            ORDER BY `id`
        ";
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = 'SELECT `id`, `name`, `email`,`address`,`phone` FROM `users` ORDER BY `id`';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }

    /**
     * @param User $user
     * @return User
     * @throws UserException
     */
    public function create(User $user): User
    {
        $query = '
            INSERT INTO `users`
                (`name`, `email`, `password`)
            VALUES
                (:name, :email, :password)
        ';

        $statement = $this->database->prepare($query);

        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $statement->bindParam('name', $name);
        $statement->bindParam('email', $email);
        $statement->bindParam('password', $password);

        $statement->execute();

        return $this->getUser((int) $this->database->lastInsertId());
    }

    /**
     * @param User $user
     * @return User
     * @throws UserException
     */
    public function update(User $user): User
    {
        $query = '
            UPDATE `users` SET `name` = :name, `phone` = :phone, `address` = :address WHERE `id` = :id
        ';

        $statement = $this->database->prepare($query);

        $id = $user->getId();
        $name = $user->getName();
        $phone = $user->getPhone();
        $address = $user->getAddress();

        $statement->bindParam('id', $id);
        $statement->bindParam('name', $name);
        $statement->bindParam('phone', $phone);
        $statement->bindParam('address', $address);

        $statement->execute();

        return $this->getUser((int)$id);
    }
}
