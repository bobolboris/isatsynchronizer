<?php

namespace App\Entity\Front;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`oc_category_description`")
 * @ORM\Entity(repositoryClass="App\Repository\Front\CategoryDescriptionRepository")
 */
class CategoryDescription extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`category_id`")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="`language_id`")
     */
    private $languageId;

    /**
     * @ORM\Column(type="string", name="`name`", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="`description`")
     */
    private $description;

    /**
     * @ORM\Column(type="string", name="`meta_title`", length=255)
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="string", name="`meta_description`", length=255)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="string", name="`meta_keyword`", length=255)
     */
    private $metaKeyword;

    /**
     * @param int $categoryId
     * @param int $languageId
     * @param string $name
     * @param string $description
     * @param string $metaTitle
     * @param string $metaDescription
     * @param string $metaKeyword
     */
    public function fill(
        int $categoryId,
        int $languageId,
        string $name,
        string $description,
        string $metaTitle,
        string $metaDescription,
        string $metaKeyword
    )
    {
        $this->id = $categoryId;
        $this->languageId = $languageId;
        $this->name = $name;
        $this->description = $description;
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->metaKeyword = $metaKeyword;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    public function setMetaKeyword(string $metaKeyword): self
    {
        $this->metaKeyword = $metaKeyword;

        return $this;
    }

}
