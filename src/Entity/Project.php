<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Cocur\Slugify\Slugify;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     * @Assert\NotBlank()
     */
    private ?string $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     * @Assert\Length(min=15)
     * @Assert\NotBlank()
     */
    private ?string $content;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private ?bool $validate;

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
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="projects")
     */
    private ?Category $category;

    /**
     * Project constructor.
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
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        if (empty($slug)) {
            $this->slug = (new Slugify())->slugify($this->title);
        } else {
            $this->slug = $slug;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    /**
     * @param bool $validate
     * @return $this
     */
    public function setValidate(?bool $validate): self
    {
        $this->validate = $validate;

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
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
