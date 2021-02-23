<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeviceRepository::class)
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=DeviceType::class, inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=CheckIn::class, mappedBy="devices")
     */
    private $checkIns;

    public function __construct()
    {
        $this->checkIns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?DeviceType
    {
        return $this->type;
    }

    public function setType(?DeviceType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|CheckIn[]
     */
    public function getCheckIns(): Collection
    {
        return $this->checkIns;
    }

    public function addCheckIn(CheckIn $checkIn): self
    {
        if (!$this->checkIns->contains($checkIn)) {
            $this->checkIns[] = $checkIn;
            $checkIn->addDevice($this);
        }

        return $this;
    }

    public function removeCheckIn(CheckIn $checkIn): self
    {
        if ($this->checkIns->removeElement($checkIn)) {
            $checkIn->removeDevice($this);
        }

        return $this;
    }
}
