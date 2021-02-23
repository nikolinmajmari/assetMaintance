<?php

namespace App\Entity;

use App\Repository\CheckInRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CheckInRepository::class)
 */
class CheckIn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $checkedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="checkIns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity=Device::class, inversedBy="checkIns")
     */
    private $devices;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheckedAt(): ?\DateTimeInterface
    {
        return $this->checkedAt;
    }

    public function setCheckedAt(\DateTimeInterface $checkedAt): self
    {
        $this->checkedAt = $checkedAt;

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

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        $this->devices->removeElement($device);

        return $this;
    }
}
