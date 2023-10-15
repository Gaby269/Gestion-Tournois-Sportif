<?php

namespace App\Entity;

use App\Repository\SportsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SportsRepository::class)]
class Sports
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomSport = null;

    #[ORM\Column]
    private ?int $nbJoueur = null;

    #[ORM\OneToMany(mappedBy: 'sportTournois', targetEntity: Tournois::class, orphanRemoval: true)]
    private Collection $tournoisSport;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'sportsUser')]
    private Collection $usersSports;

    #[ORM\OneToMany(mappedBy: 'sport', targetEntity: Equipes::class, orphanRemoval: true)]
    private Collection $equipesSport;

    public function __construct()
    {
        $this->tournoisSport = new ArrayCollection();
        $this->usersSports = new ArrayCollection();
        $this->equipesSport = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSport(): ?string
    {
        return $this->nomSport;
    }

    public function setNomSport(string $nomSport): static
    {
        $this->nomSport = $nomSport;

        return $this;
    }

    public function getNbJoueur(): ?int
    {
        return $this->nbJoueur;
    }

    public function setNbJoueur(int $nbJoueur): static
    {
        $this->nbJoueur = $nbJoueur;

        return $this;
    }




    /**
     * @return Collection<int, Tournois>
     */
    public function getTournoisSport(): Collection
    {
        return $this->tournoisSport;
    }

    public function addTournoisSport(Tournois $tournoi): static
    {
        if (!$this->tournoisSport->contains($tournoi)) {
            $this->tournoisSport->add($tournoi);
            $tournoi->setSportTournois($this);
        }

        return $this;
    }

    public function removeTournoisSport(Tournois $tournoi): static
    {
        if ($this->tournoisSport->removeElement($tournoi)) {
            // set the owning side to null (unless already changed)
            if ($tournoi->getSportTournois() === $this) {
                $tournoi->setSportTournois(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsersSports(): Collection
    {
        return $this->usersSports;
    }

    public function addUsersSport(Users $user): static
    {
        if (!$this->usersSports->contains($user)) {
            $this->usersSports->add($user);
            $user->addSportsUser($this);
        }

        return $this;
    }

    public function removeUsersSport(Users $user): static
    {
        if ($this->usersSports->removeElement($user)) {
            $user->removeSportsUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipes>
     */
    public function getEquipesSport(): Collection
    {
        return $this->equipesSport;
    }

    public function addEquipesSport(Equipes $equipe): static
    {
        if (!$this->equipesSport->contains($equipe)) {
            $this->equipesSport->add($equipe);
            $equipe->setSport($this);
        }

        return $this;
    }

    public function removeEquipesSport(Equipes $equipe): static
    {
        if ($this->equipesSport->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getSport() === $this) {
                $equipe->setSport(null);
            }
        }

        return $this;
    }
}