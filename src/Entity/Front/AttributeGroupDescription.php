<?php

namespace App\Entity\Front;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`oc_attribute_group_description`")
 * @ORM\Entity(repositoryClass="App\Repository\Front\AttributeGroupDescriptionRepository")
 */
class AttributeGroupDescription extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`attribute_group_id`")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="`language_id`")
     */
    private $languageId;

    /**
     * @ORM\Column(type="string", name="`name`", length=64)
     */
    private $name;

    /**
     * @param int $attributeGroupId
     * @param int $languageId
     * @param string $name
     */
    public function fill(
        int $attributeGroupId,
        int $languageId,
        string $name
    )
    {
        $this->id = $attributeGroupId;
        $this->languageId = $languageId;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
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
}
