<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tournament_name;

    /**
     * @ORM\OneToOne(targetEntity=Division::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $divisionA;

    /**
     * @ORM\OneToOne(targetEntity=Division::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $divisionB;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class)
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="tournament")
     */
    private $results;

    /**
     * @ORM\OneToOne(targetEntity=Playoff::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $playoff;

    public function __toString()
    {
        return $this->tournament_name;
    }

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournamentName(): ?string
    {
        return $this->tournament_name;
    }

    public function setTournamentName(?string $tournament_name): self
    {
        $this->tournament_name = $tournament_name;

        return $this;
    }

    public function getDivisionA(): ?Division
    {
        return $this->divisionA;
    }

    public function setDivisionA(Division $divisionA): self
    {
        $this->divisionA = $divisionA;

        return $this;
    }

    public function getDivisionB(): ?Division
    {
        return $this->divisionB;
    }

    public function setDivisionB(Division $divisionB): self
    {
        $this->divisionB = $divisionB;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setTournament($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getTournament() === $this) {
                $result->setTournament(null);
            }
        }

        return $this;
    }

    public function getPlayoff(): ?Playoff
    {
        return $this->playoff;
    }

    public function setPlayoff(Playoff $playoff): self
    {
        $this->playoff = $playoff;

        return $this;
    }

    public function computePlayoffTeams()
    {
        $sort_desc = function ($a,$b)
        {
            if ($a->getScore()==$b->getScore()) return 0;
            return ($a->getScore()>$b->getScore())?-1:1;
        };
        $resultsA = $this->divisionA->getResults()->getValues();
        uasort($resultsA, $sort_desc );
        $results = array_slice($resultsA, 0, 4);
        $resultsB = $this->getDivisionB()->getResults()->getValues();
        uasort($resultsB,$sort_desc);
        $results = array_merge($results, array_slice($resultsB, 0, 4));
        $teams = [];
        foreach($results as $result) {
            $teams[] = $result->getTeam();
        }
        return $teams;
    }
}
