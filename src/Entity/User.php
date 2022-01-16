<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * Class User
 * @package App\Entity
 */
class User
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $address;

    /**
     * @var string|null
     */
    private ?string $phone;

    /**
     * @return object
     */
    public function toJson(): object
    {
        return json_decode((string)json_encode(get_object_vars($this)), false);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function updateName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function updateEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function updatePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function updatePhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function updateAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function transferToObject(array $row): self
    {
        $user = new self();

        $user->setId($row['id']);
        $user->setName($row['name']);
        $user->setEmail($row['email']);
        $user->setAddress($row['address']);
        $user->setPhone($row['phone']);

        return $user;
    }
}
