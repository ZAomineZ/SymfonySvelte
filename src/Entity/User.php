<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60)
     */
    private ?string $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60)
     */
    private ?string $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=60)
     */
    private ?string $password;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=15)
     */
    private ?string $roles;

    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created_at;

    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $updated_at;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
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
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->roles];
    }

    /**
     * @param string $roles
     * @return $this
     */
    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    /**
     * @param DateTimeInterface $updated_at
     * @return $this
     */
    public function setUpdatedAt(DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
