<?php

namespace App\Entity;

use App\Repository\RencontresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RencontresRepository::class)]
class Rencontres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime_at = null;

    #[ORM\Column(nullable: true)]
    private ?int $poule = null;

    #[ORM\Column(nullable: true)]
    private ?int $tourJournee = null;

    #[ORM\ManyToOne(inversedBy: 'gagnantRencontres')]
    private ?Equipes $gagnant = null;

    #[ORM\ManyToMany(targetEntity: Equipes::class, mappedBy: 'participeRencontres')]
    private Collection $listeEquipes;

    #[ORM\ManyToOne(inversedBy: 'listeRencontres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournois $appartenanceTournoi = null;

    public function __construct()
    {
        $this->listeEquipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTimeAt(): ?\DateTimeInterface
    {
        return $this->dateTime_at;
    }

    public function setDateTimeAt(\DateTimeInterface $dateTime_at): static
    {
        $this->dateTime_at = $dateTime_at;

        return $this;
    }

    public function getPoule(): ?int
    {
        return $this->poule;
    }

    public function setPoule(?int $poule): static
    {
        $this->poule = $poule;

        return $this;
    }

    public function getTourJournee(): ?int
    {
        return $this->tourJournee;
    }

    public function setTourJournee(?int $tourJournee): static
    {
        $this->tourJournee = $tourJournee;

        return $this;
    }

    public function getGagnant(): ?Equipes
    {
        return $this->gagnant;
    }

    public function setGagnant(?Equipes $gagnant): static
    {
        $this->gagnant = $gagnant;

        return $this;
    }

    /**
     * @return Collection<int, Equipes>
     */
    public function getListeEquipes(): Collection
    {
        return $this->listeEquipes;
    }

    public function addListeEquipe(Equipes $listeEquipe): static
    {
        if (!$this->listeEquipes->contains($listeEquipe)) {
            $this->listeEquipes->add($listeEquipe);
            $listeEquipe->addParticipeRencontre($this);
        }

        return $this;
    }

    public function removeListeEquipe(Equipes $listeEquipe): static
    {
        if ($this->listeEquipes->removeElement($listeEquipe)) {
            $listeEquipe->removeParticipeRencontre($this);
        }

        return $this;
    }

    public function getappartenanceTournoi(): ?Tournois
    {
        return $this->appartenanceTournoi;
    }

    public function setappartenanceTournoi(?Tournois $appartenanceTournoi): static
    {
        $this->appartenanceTournoi = $appartenanceTournoi;

        return $this;
    }
}