<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use DateTime;

/**
 * @ORM\Table(name="`orders`")
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    /**
     * @var int|null $id
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var int|null $frontId
     * @ORM\Column(type="integer", name="`front_id`")
     */
    protected $frontId;

    /**
     * @var int|null $backId
     * @ORM\Column(type="integer", name="`back_id`")
     */
    protected $backId;

    /**
     * @var DateTimeInterface|null $createdAt
     * @ORM\Column(type="datetime", name="`created_at`", nullable=true)
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface|null $updatedAt
     * @ORM\Column(type="datetime", name="`updated_at`", nullable=true)
     */
    protected $updatedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getFrontId(): ?int
    {
        return $this->frontId;
    }

    /**
     * @param int $frontId
     * @return Order
     */
    public function setFrontId(int $frontId): self
    {
        $this->frontId = $frontId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getBackId(): ?int
    {
        return $this->backId;
    }

    /**
     * @param int $backId
     * @return Order
     */
    public function setBackId(int $backId): self
    {
        $this->backId = $backId;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     * @return Order
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     * @return Order
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new DateTime('now'));

        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }
}
