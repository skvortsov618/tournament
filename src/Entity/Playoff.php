<?php

namespace App\Entity;

use App\Repository\PlayoffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayoffRepository::class)
 */
class Playoff
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
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="playoff")
     */
    private $results;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $quarter1;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $quarter2;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $quarter3;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $quarter4;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $half1;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $half2;

    /**
     * @ORM\OneToOne(targetEntity=Game::class, cascade={"persist", "remove"})
     */
    private $final;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->results = new ArrayCollection();
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
            $result->setPlayoff($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getPlayoff() === $this) {
                $result->setPlayoff(null);
            }
        }

        return $this;
    }

    public function getQuarter1(): ?Game
    {
        return $this->quarter1;
    }

    public function setQuarter1(?Game $quarter1): self
    {
        $this->quarter1 = $quarter1;

        return $this;
    }

    public function getQuarter2(): ?Game
    {
        return $this->quarter2;
    }

    public function setQuarter2(?Game $quarter2): self
    {
        $this->quarter2 = $quarter2;

        return $this;
    }

    public function getQuarter3(): ?Game
    {
        return $this->quarter3;
    }

    public function setQuarter3(?Game $quarter3): self
    {
        $this->quarter3 = $quarter3;

        return $this;
    }

    public function getQuarter4(): ?Game
    {
        return $this->quarter4;
    }

    public function setQuarter4(?Game $quarter4): self
    {
        $this->quarter4 = $quarter4;

        return $this;
    }

    public function getHalf1(): ?Game
    {
        return $this->half1;
    }

    public function setHalf1(?Game $half1): self
    {
        $this->half1 = $half1;

        return $this;
    }

    public function getHalf2(): ?Game
    {
        return $this->half2;
    }

    public function setHalf2(?Game $half2): self
    {
        $this->half2 = $half2;

        return $this;
    }

    public function getFinal(): ?Game
    {
        return $this->final;
    }

    public function setFinal(?Game $final): self
    {
        $this->final = $final;

        return $this;
    }

    public function setQuarters() {
        $teams = $this->getTeams()->getValues();
        $this->quarter1->setTeamA($teams[0]);
        $this->quarter1->setTeamB($teams[1]);

        $this->quarter2->setTeamA($teams[2]);
        $this->quarter2->setTeamB($teams[3]);

        $this->quarter3->setTeamA($teams[4]);
        $this->quarter3->setTeamB($teams[5]);

        $this->quarter4->setTeamA($teams[6]);
        $this->quarter4->setTeamB($teams[7]);
    }

    public function autoplayQuarters() {
        $this->quarter1->autoplay();
        $this->quarter2->autoplay();
        $this->quarter3->autoplay();
        $this->quarter4->autoplay();
    }

    public function setHalfs() {
        $this->half1->setTeamA($this->quarter1->getGoalsA()>=$this->quarter1->getGoalsB()? $this->quarter1->getTeamA() : $this->quarter1->getTeamB());
        $this->half1->setTeamB($this->quarter2->getGoalsA()>=$this->quarter2->getGoalsB()? $this->quarter2->getTeamA() : $this->quarter2->getTeamB());
        $this->half2->setTeamA($this->quarter3->getGoalsA()>=$this->quarter3->getGoalsB()? $this->quarter3->getTeamA() : $this->quarter3->getTeamB());
        $this->half2->setTeamB($this->quarter4->getGoalsA()>=$this->quarter4->getGoalsB()? $this->quarter4->getTeamA() : $this->quarter4->getTeamB());
    }

    public function autoplayHalfs() {
        $this->half1->autoplay();
        $this->half2->autoplay();
    }

    public function setPlayoffFinal() {
        $this->final->setTeamA($this->half1->getGoalsA()>=$this->half1->getGoalsB()? $this->half1->getTeamA() : $this->half1->getTeamB());
        $this->final->setTeamB($this->half2->getGoalsA()>=$this->half2->getGoalsB()? $this->half2->getTeamA() : $this->half2->getTeamB());
    }

    public function autoplayFinal() {
        $this->final->autoplay();
    }

    public function computeResults() {
        $results = [];
        if ($this->final->getGoalsA() >= $this->final->getGoalsB()) {
            return $this->final->getTeamA();
//            $this->addResult(Result::withTeamAndScore($this->final->getTeamA(), 6000));
//            $this->addResult(Result::withTeamAndScore($this->final->getTeamB(), 5000));
        } else {
            return $this->final->getTeamB();
        }
    }
}
