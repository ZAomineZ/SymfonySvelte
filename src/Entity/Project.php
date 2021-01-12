<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use Cocur\Slugify\Slugify;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project implements TaggableInterface
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
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    private $tags;

    /**
     * @var string|null
     */
    private ?string $tagsText;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
        $this->tags = new ArrayCollection();
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

    /**
     * @param TagInterface $tag
     */
    public function addTag(TagInterface $tag): void
    {
        if (!$this->hasTag($tag)) {
            $this->tags->add($this);
        }
    }

    /**
     * @return array
     */
    public function getTagNames(): array
    {
        return !empty($this->tagsText) ? array_map('trim', explode(',', $this->tagsText)) : [];
    }

    /**
     * @return iterable
     */
    public function getTags(): iterable
    {
        return $this->tags;
    }

    /**
     * @param TagInterface $tag
     * @return bool
     */
    public function hasTag(TagInterface $tag): bool
    {
        return $this->tags->contains($tag);
    }

    /**
     * @param TagInterface $tag
     */
    public function removeTag(TagInterface $tag): void
    {
        $this->tags->remove($tag);
    }

    /**
     * @return string|null
     */
    public function getTagsText(): ?string
    {
        $this->tagsText = implode(', ', $this->tags->toArray());

        return $this->tagsText;
    }

    /**
     * @param string|null $tagsText
     */
    public function setTagsText(?string $tagsText): void
    {
        $this->tagsText = $tagsText;
    }
}
