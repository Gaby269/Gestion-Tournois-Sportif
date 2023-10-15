<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé, veuillez en choisir un autre !')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nomUser = null;

    #[ORM\Column(length: 100)]
    private ?string $prenomUser = null;

    #[ORM\Column(length: 150)]
    private ?string $villeUser = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private \DateTimeInterface $created_at;

    #[ORM\ManyToOne(inversedBy: 'usersRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Roles $roleUsers = null;

    #[ORM\ManyToMany(targetEntity: Sports::class, inversedBy: 'usersSports')]
    private Collection $sportsUser;

    #[ORM\OneToOne(mappedBy: 'capitaine', cascade: ['persist', 'remove'])]
    private ?Equipes $capitaineEquipe = null;

    #[ORM\ManyToMany(targetEntity: Equipes::class, mappedBy: 'membres')]
    private Collection $membreEquipes;

    #[ORM\ManyToMany(targetEntity: Tournois::class, mappedBy: 'listeGestionnaires')]
    private Collection $gestionnaireTournois;

    public function __construct()
    {
        $this->sportsUser = new ArrayCollection();
        $this->membreEquipes = new ArrayCollection();
        $this->gestionnaireTournois = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): static
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }

    public function getVilleUser(): ?string
    {
        return $this->villeUser;
    }

    public function setVilleUser(string $villeUser): static
    {
        $this->villeUser = $villeUser;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRoleUser(): ?Roles
    {
        return $this->roleUsers;
    }

    public function setRoleUser(Roles $rolesUser): static
    {
        $this->roleUsers = $rolesUser;

        return $this;
    }

    public function removeRoleUser(): static
    {
        $this->roleUsers = null;

        return $this;
    }

    /**
     * @return Collection<int, Sports>
     */
    public function getSportsUser(): Collection
    {
        return $this->sportsUser;
    }

    public function addSportsUser(Sports $sportsUser): static
    {
        if (!$this->sportsUser->contains($sportsUser)) {
            $this->sportsUser->add($sportsUser);
        }

        return $this;
    }

    public function removeSportsUser(Sports $sportsUser): static
    {
        $this->sportsUser->removeElement($sportsUser);

        return $this;
    }

    public function getCapitaineEquipe(): ?Equipes
    {
        return $this->capitaineEquipe;
    }

    public function setCapitaineEquipe(Equipes $capitaineEquipe): static
    {
        // set the owning side of the relation if necessary
        if ($capitaineEquipe->getCapitaine() !== $this) {
            $capitaineEquipe->setCapitaine($this);
        }

        $this->capitaineEquipe = $capitaineEquipe;

        return $this;
    }

    /**
     * @return Collection<int, Equipes>
     */
    public function getMembreEquipes(): Collection
    {
        return $this->membreEquipes;
    }

    public function addMembreEquipe(Equipes $membreEquipe): static
    {
        if (!$this->membreEquipes->contains($membreEquipe)) {
            $this->membreEquipes->add($membreEquipe);
            $membreEquipe->addMembre($this);
        }

        return $this;
    }

    public function removeMembreEquipe(Equipes $membreEquipe): static
    {
        if ($this->membreEquipes->removeElement($membreEquipe)) {
            $membreEquipe->removeMembre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tournois>
     */
    public function getGestionnaireTournois(): Collection
    {
        return $this->gestionnaireTournois;
    }

    public function addGestionnaireTournoi(Tournois $gestionnaireTournoi): static
    {
        if (!$this->gestionnaireTournois->contains($gestionnaireTournoi)) {
            $this->gestionnaireTournois->add($gestionnaireTournoi);
            $gestionnaireTournoi->addListeGestionnaire($this);
        }

        return $this;
    }

    public function removeGestionnaireTournoi(Tournois $gestionnaireTournoi): static
    {
        if ($this->gestionnaireTournois->removeElement($gestionnaireTournoi)) {
            $gestionnaireTournoi->removeListeGestionnaire($this);
        }

        return $this;
    }



}