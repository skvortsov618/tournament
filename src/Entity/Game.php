<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $teamA;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $teamB;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalsA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $goalsB;

    /**
     * @ORM\ManyToOne(targetEntity=Division::class, inversedBy="games")
     */
    private $division;

    public function __toString() {
        return $this->getTeamA()." ".$this->getGoalsA().":".$this->getGoalsB().$this->getTeamB();
    }

    public static function withTeam(Team $teamA, Team $teamB): Game {
        $new = new Game();
        $new->setTeamA($teamA);
        $new->setTeamB($teamB);
        return $new;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamA(): ?Team
    {
        return $this->teamA;
    }

    public function setTeamA(?Team $teamA): self
    {
        $this->teamA = $teamA;

        return $this;
    }

    public function getTeamB(): ?Team
    {
        return $this->teamB;
    }

    public function setTeamB(?Team $teamB): self
    {
        $this->teamB = $teamB;

        return $this;
    }

    public function getGoalsA(): ?int
    {
        return $this->goalsA;
    }

    public function setGoalsA(?int $goalsA): self
    {
        $this->goalsA = $goalsA;

        return $this;
    }

    public function getGoalsB(): ?int
    {
        return $this->goalsB;
    }

    public function setGoalsB(?int $goalsB): self
    {
        $this->goalsB = $goalsB;

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

    public function autoplay()
    {
        $this->setGoalsA(random_int(0, 10));
        $this->setGoalsB(random_int(0, 10));
    }
}
