<?php

namespace App\Entity;

use App\Repository\TournoisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournoisRepository::class)]
class Tournois
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomTournoi = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endTime_at = null;

    #[ORM\Column]
    private ?int $dureeTournoi = null;

    #[ORM\Column(length: 150)]
    private ?string $adressePostal = null;

    #[ORM\Column(length: 5)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    private ?string $villeTournoi = null;


    #[ORM\Column]
    private ?int $nbEquipes = null;

    #[ORM\Column(length: 100)]
    private ?string $etatTournoi = null;

    #[ORM\Column(length: 100)]
    private ?string $typeTournoi = null;

    #[ORM\ManyToOne(inversedBy: 'tournoisSport')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sports $sportTournois = null;

    #[ORM\ManyToMany(targetEntity: Equipes::class, mappedBy: 'concourtTournois')]
    private Collection $listeEquipes;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'gestionnaireTournois')]
    private Collection $listeGestionnaires;

    #[ORM\OneToMany(mappedBy: 'appartenanceTournoi', targetEntity: Rencontres::class, orphanRemoval: true)]
    private Collection $listeRencontres;

    #[ORM\ManyToOne(inversedBy: 'vainqueurTournois')]
    private ?Equipes $vainqueur = null;

    public function __construct()
    {
        $this->listeEquipes = new ArrayCollection();
        $this->listeGestionnaires = new ArrayCollection();
        $this->listeRencontres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(string $nomTournoi): static
    {
        $this->nomTournoi = $nomTournoi;

        return $this;
    }

    public function getStartTimeAt(): ?\DateTimeImmutable
    {
        return $this->startTime_at;
    }

    public function setStartTimeAt(\DateTimeImmutable $startTime_at): static
    {
        $this->startTime_at = $startTime_at;

        return $this;
    }

    public function getEndTimeAt(): ?\DateTimeImmutable
    {
        return $this->endTime_at;
    }

    public function setEndTimeAt(\DateTimeImmutable $endTime_at): static
    {
        $this->endTime_at = $endTime_at;

        return $this;
    }

    public function getDureeTournoi(): ?int
    {
        return $this->dureeTournoi;
    }

    public function setDureeTournoi(int $dureeTournoi): static
    {
        $this->dureeTournoi = $dureeTournoi;

        return $this;
    }

    public function getAdressePostal(): ?string
    {
        return $this->adressePostal;
    }

    public function setAdressePostal(string $adressePostal): static
    {
        $this->adressePostal = $adressePostal;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVilleTournoi(): ?string
    {
        return $this->villeTournoi;
    }

    public function setVilleTournoi(string $villeTounoi): static
    {
        $this->villeTournoi = $villeTounoi;

        return $this;
    }

    public function getNbEquipes(): ?int
    {
        return $this->nbEquipes;
    }

    public function setNbEquipes(int $nbEquipes): static
    {
        $this->nbEquipes = $nbEquipes;

        return $this;
    }

    public function getSportTournois(): ?Sports
    {
        return $this->sportTournois;
    }

    public function setSportTournois(?Sports $sportTournois): static
    {
        $this->sportTournois = $sportTournois;

        return $this;
    }

    public function getEtatTournoi(): ?string
    {
        return $this->etatTournoi;
    }

    public function setEtatTournoi(string $etatTournoi): static
    {
        $this->etatTournoi = $etatTournoi;

        return $this;
    }

    public function getTypeTournoi(): ?string
    {
        return $this->typeTournoi;
    }

    public function setTypeTournoi(string $typeTournoi): static
    {
        $this->typeTournoi = $typeTournoi;

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
            $listeEquipe->addConcourtTournoi($this);
        }

        return $this;
    }

    public function removeListeEquipe(Equipes $listeEquipe): static
    {
        if ($this->listeEquipes->removeElement($listeEquipe)) {
            $listeEquipe->removeConcourtTournoi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getListeGestionnaires(): Collection
    {
        return $this->listeGestionnaires;
    }

    public function addListeGestionnaire(Users $listeGestionnaire): static
    {
        if (!$this->listeGestionnaires->contains($listeGestionnaire)) {
            $this->listeGestionnaires->add($listeGestionnaire);
        }

        return $this;
    }

    public function removeListeGestionnaire(Users $listeGestionnaire): static
    {
        $this->listeGestionnaires->removeElement($listeGestionnaire);

        return $this;
    }

    /**
     * @return Collection<int, Rencontres>
     */
    public function getListeRencontres(): Collection
    {
        return $this->listeRencontres;
    }

    public function addListeRencontre(Rencontres $listeRencontre): static
    {
        if (!$this->listeRencontres->contains($listeRencontre)) {
            $this->listeRencontres->add($listeRencontre);
            $listeRencontre->setappartenanceTournoi($this);
        }

        return $this;
    }

    public function removeListeRencontre(Rencontres $listeRencontre): static
    {
        if ($this->listeRencontres->removeElement($listeRencontre)) {
            // set the owning side to null (unless already changed)
            if ($listeRencontre->getappartenanceTournoi() === $this) {
                $listeRencontre->setappartenanceTournoi(null);
            }
        }

        return $this;
    }

    public function getVainqueur(): ?Equipes
    {
        return $this->vainqueur;
    }

    public function setVainqueur(?Equipes $vainqueur): static
    {
        $this->vainqueur = $vainqueur;

        return $this;
    }

}