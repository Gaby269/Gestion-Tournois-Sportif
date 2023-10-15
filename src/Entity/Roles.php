<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomRole = null;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'roleUsers')]
    private Collection $usersRoles;

    public function __construct()
    {
        $this->usersRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRole(): ?string
    {
        return $this->nomRole;
    }

    public function setNomRole(string $nomRole): static
    {
        $this->nomRole = $nomRole;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsersRoles(): Collection
    {
        return $this->usersRoles;
    }

    public function addUserRoles(Users $usersRoles): static
    {
        if (!$this->usersRoles->contains($usersRoles)) {
            $this->usersRoles->add($usersRoles);
            $usersRoles->setRoleUser($this);
        }

        return $this;
    }

    public function removeUserRoles(Users $usersRoles): static
    {
        if ($this->usersRoles->removeElement($usersRoles)) {
            $usersRoles->removeRoleUser();
        }

        return $this;
    }
}