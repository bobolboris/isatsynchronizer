<?php

namespace App\Entity\Front;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`oc_customer_group_description`")
 * @ORM\Entity(repositoryClass="App\Repository\Front\CustomerGroupDescriptionRepository")
 */
class CustomerGroupDescription extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="`customer_group_id`")
     */
    private $customerGroupId;

    /**
     * @ORM\Column(type="integer", name="`language_id`")
     */
    private $languageId;

    /**
     * @ORM\Column(type="string", name="`name`", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="string", name="`description`", length=255)
     */
    private $description;

    /**
     * @param int $languageId
     * @param string $name
     * @param string $description
     */
    public function fill(
        int $languageId,
        string $name,
        string $description
    )
    {
        $this->languageId = $languageId;
        $this->name = $name;
        $this->description = $description;
    }


    public function getCustomerGroupId(): ?int
    {
        return $this->customerGroupId;
    }

    public function setCustomerGroupId(int $customerGroupId): self
    {
        $this->customerGroupId = $customerGroupId;

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
}
