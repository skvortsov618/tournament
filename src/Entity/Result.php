<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity=Division::class, inversedBy="results")
     */
    private $division;

    /**
     * @ORM\ManyToOne(targetEntity=Playoff::class, inversedBy="results")
     */
    private $playoff;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="results", cascade={"persist"})
     */
    private $tournament;

    public function __toString()
    {
        return $this->score!=null?$this->score:"";
    }


    public static function withTeam(Team $team) : Result {
        $new = new Result();
        $new->setTeam($team);
        return $new;
    }
    public static function withTeamAndScore(Team $team, int $score) : Result {
        $new = new Result();
        $new->setTeam($team);
        $new->setScore($score);
        return $new;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getDivision(): ?Division
    {
        return $this->division;
    }

    public function setDivision(?Division $division): self
    {
        $this->division = $division;

        return $this;
    }

    public function getPlayoff(): ?Playoff
    {
        return $this->playoff;
    }

    public function setPlayoff(?Playoff $playoff): self
    {
        $this->playoff = $playoff;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}
