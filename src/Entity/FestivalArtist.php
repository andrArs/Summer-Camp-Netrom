<?php

namespace App\Entity;

use App\Repository\FestivalArtistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalArtistRepository::class)]
class FestivalArtist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'festivalArtists')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Festival $festival = null;

    #[ORM\ManyToOne(inversedBy: 'festivalArtists')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Artist $artist = null;

    #[ORM\ManyToOne(inversedBy: 'festivalArtists')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Schedule $schedule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }
}
