<?php

namespace App\Entity;

use App\Repository\EquipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipesRepository::class)]
class Equipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomEquipe = null;

    #[ORM\Column]
    private ?int $niveau = null;

    #[ORM\Column(length: 150)]
    private ?string $adresseMail = null;

    #[ORM\Column(length: 10)]
    private ?string $numeroTel = null;

    #[ORM\OneToOne(inversedBy: 'capitaineEquipe', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $capitaine = null;

    #[ORM\ManyToOne(inversedBy: 'equipesSport')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sports $sport = null;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'membreEquipes')]
    private Collection $membres;

    #[ORM\ManyToMany(targetEntity: Tournois::class, inversedBy: 'listeEquipes')]
    private Collection $concourtTournois;

    #[ORM\ManyToMany(targetEntity: Rencontres::class, inversedBy: 'listeEquipes')]
    private Collection $participeRencontres;

    #[ORM\OneToMany(targetEntity: Rencontres::class, mappedBy: 'gagnant', )]
    private Collection $gagnantRencontres;

    #[ORM\OneToMany(targetEntity: Tournois::class, mappedBy: 'vainqueur')]
    private Collection $vainqueurTournois;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->concourtTournois = new ArrayCollection();
        $this->participeRencontres = new ArrayCollection();
        $this->gagnantRencontres = new ArrayCollection();
        $this->vainqueurTournois = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): static
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresseMail;
    }

    public function setAdresseMail(string $adresseMail): static
    {
        $this->adresseMail = $adresseMail;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(string $numeroTel): static
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getCapitaine(): ?Users
    {
        return $this->capitaine;
    }

    public function setCapitaine(Users $capitaine): static
    {
        $this->capitaine = $capitaine;

        return $this;
    }

    public function getSport(): ?Sports
    {
        return $this->sport;
    }

    public function setSport(?Sports $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Users $membre): static
    {
        if (!$this->membres->contains($membre)) {
            $this->membres->add($membre);
        }

        return $this;
    }

    public function removeMembre(Users $membre): static
    {
        $this->membres->removeElement($membre);

        return $this;
    }

    /**
     * @return Collection<int, Tournois>
     */
    public function getConcourtTournois(): Collection
    {
        return $this->concourtTournois;
    }

    public function addConcourtTournoi(Tournois $concourtTournoi): static
    {
        if (!$this->concourtTournois->contains($concourtTournoi)) {
            $this->concourtTournois->add($concourtTournoi);
        }

        return $this;
    }

    public function removeConcourtTournoi(Tournois $concourtTournoi): static
    {
        $this->concourtTournois->removeElement($concourtTournoi);

        return $this;
    }

    /**
     * @return Collection<int, Rencontres>
     */
    public function getGagnantRencontres(): Collection
    {
        return $this->gagnantRencontres;
    }

    public function addGagnantRencontre(Rencontres $gagnantRencontre): static
    {
        if (!$this->gagnantRencontres->contains($gagnantRencontre)) {
            $this->gagnantRencontres->add($gagnantRencontre);
            $gagnantRencontre->setGagnant($this);
        }

        return $this;
    }

    public function removeGagnantRencontre(Rencontres $gagnantRencontre): static
    {
        if ($this->gagnantRencontres->removeElement($gagnantRencontre)) {
            // set the owning side to null (unless already changed)
            if ($gagnantRencontre->getGagnant() === $this) {
                $gagnantRencontre->setGagnant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rencontres>
     */
    public function getParticipeRencontres(): Collection
    {
        return $this->participeRencontres;
    }

    public function addParticipeRencontre(Rencontres $participeRencontre): static
    {
        if (!$this->participeRencontres->contains($participeRencontre)) {
            $this->participeRencontres->add($participeRencontre);
        }

        return $this;
    }

    public function removeParticipeRencontre(Rencontres $participeRencontre): static
    {
        $this->participeRencontres->removeElement($participeRencontre);

        return $this;
    }

    /**
     * @return Collection<int, Tournois>
     */
    public function getVainqueurTournois(): Collection
    {
        return $this->vainqueurTournois;
    }

    public function addVainqueurTournoi(Tournois $vainqueurTournoi): static
    {
        if (!$this->vainqueurTournois->contains($vainqueurTournoi)) {
            $this->vainqueurTournois->add($vainqueurTournoi);
            $vainqueurTournoi->setVainqueur($this);
        }

        return $this;
    }

    public function removeVainqueurTournoi(Tournois $vainqueurTournoi): static
    {
        if ($this->vainqueurTournois->removeElement($vainqueurTournoi)) {
            // set the owning side to null (unless already changed)
            if ($vainqueurTournoi->getVainqueur() === $this) {
                $vainqueurTournoi->setVainqueur(null);
            }
        }

        return $this;
    }
}