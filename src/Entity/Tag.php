<?php

namespace App\Entity;

use Beelab\TagBundle\Tag\TagInterface;
use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag implements TagInterface
{

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected ?int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=60)
     */
    protected ?string $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    protected ?string $slug;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    protected ?DateTime $created_at;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime")
     */
    protected ?DateTime $updated_at;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug(?string $slug)
    {
        if (empty($slug)) {
            $this->slug = (new Slugify())->slugify($this->name);
        } else {
            $this->slug = $slug;
        }
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime|null $created_at
     */
    public function setCreatedAt(?DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime|null $updated_at
     */
    public function setUpdatedAt(?DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}