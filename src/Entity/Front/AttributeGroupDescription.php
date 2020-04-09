<?php

namespace App\Entity\Front;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`oc_attribute_group_description`")
 * @ORM\Entity(repositoryClass="App\Repository\Front\AttributeGroupDescriptionRepository")
 */
class AttributeGroupDescription extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`attribute_group_id`")
     */
    private $attributeGroupId;

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
        $this->attributeGroupId = $attributeGroupId;
        $this->languageId = $languageId;
        $this->name = $name;
    }

    public function getAttributeGroupId(): ?int
    {
        return $this->attributeGroupId;
    }

    public function setAttributeGroupId(int $attributeGroupId): self
    {
        $this->attributeGroupId = $attributeGroupId;

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
