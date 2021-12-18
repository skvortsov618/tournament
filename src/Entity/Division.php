<?php

namespace App\Entity;

use App\Repository\DivisionRepository;
use App\Repository\ResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DivisionRepository::class)
 */
class Division
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class)
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="division")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="division", cascade={"persist"})
     */
    private $results;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }

    public static function withTeams($teams) : Division
    {
        $new = new Division();
        $new->teams = new ArrayCollection($teams);
//        $results = [];
//        foreach ($teams as $team) {
//            $results[] = Result::withTeam($team);
//        }
//        $new->results = new ArrayCollection($results);
        return $new;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setDivision($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getDivision() === $this) {
                $game->setDivision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function getResult(Team $team)
    {
        foreach ($this->results as $result) {
            if ($result->getTeam()->getId() == $team->getId()) {
                return $result;
            }
        }
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setDivision($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getDivision() === $this) {
                $result->setDivision(null);
            }
        }

        return $this;
    }

    public function computeResults()
    {
        $games = $this->getGames()->getValues();
        $scores=[];
        foreach ($games as $game) {
            if (!array_key_exists($game->getTeamA()->getId(), $scores))
            {
                $scores[$game->getTeamA()->getId()] = 0;
            }
            $scores[$game->getTeamA()->getId()] += $game->getGoalsA();
            if (!array_key_exists($game->getTeamB()->getId(), $scores))
            {
                $scores[$game->getTeamB()->getId()] = 0;
            }
            $scores[$game->getTeamB()->getId()] += $game->getGoalsB();
        }
        foreach ($this->getResults()->getValues() as $result) {
            foreach ($scores as $x=>$score) {
                if ($x == $result->getTeam()->getId()) {
                    $result->setScore($scores[$x]);
                }
            }
        }

    }
}
