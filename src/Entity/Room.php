<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $floor;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $building;

    /**
     * @ORM\OneToMany(targetEntity=Device::class, mappedBy="room", orphanRemoval=true)
     */
    private $devices;

    /**
     * @ORM\OneToMany(targetEntity=CheckIn::class, mappedBy="room", orphanRemoval=true)
     */
    private $checkIns;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->checkIns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

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
            $device->setRoom($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        if ($this->devices->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getRoom() === $this) {
                $device->setRoom(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
        // TODO: Implement __toString() method.
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
            $checkIn->setRoom($this);
        }

        return $this;
    }

    public function removeCheckIn(CheckIn $checkIn): self
    {
        if ($this->checkIns->removeElement($checkIn)) {
            // set the owning side to null (unless already changed)
            if ($checkIn->getRoom() === $this) {
                $checkIn->setRoom(null);
            }
        }

        return $this;
    }

    public function getLastCheckin(){
        $now = (new \DateTime("now"))->format("y-m-d");
        foreach ($this->checkIns as $checkIn){
            if($checkIn->getCheckedAt()->format("y-m-d")===$now){
                return $checkIn;
            }
        }
        return null;

    }

    public function getPreviousDayCheckin(){
        $yesterday = (date_sub(new \DateTime("now"),new \DateInterval("P1D")))->format("y-m-d");
        foreach ($this->checkIns as $checkIn){
            if($checkIn->getCheckedAt()->format("y-m-d")==$yesterday){
                return $checkIn;
            }
        }
        return null;
    }
}
