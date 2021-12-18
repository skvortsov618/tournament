<?php
namespace App\DataFixtures;
use App\Entity\Division;
use App\Entity\Game;
use App\Entity\Playoff;
use App\Entity\Question;
use App\Entity\Result;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Factory\TeamFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //just to add some teams
        //TeamFactory::new()->createMany(16);

//        $teamslugs = TeamFactory::faker()->unique->words(16);
//        $teams=[];
//        for ($i=0; $i<16;$i++){
//            $teams[$i] = Team::withSlug($teamslugs[$i]);
//            $manager->persist($teams[$i]);
//        }
//        $games=[];
//        for($i=0; $i<16;$i++) {
//            for($j=$i+1; $j<16; $j++) {
//                $game = Game::withTeam($teams[$i], $teams[$j]);
//                $manager->persist($game);
//            }
//        }
        $tournament = new Tournament();
        $teamslugs = TeamFactory::faker()->unique->words(16);
        $teams=[];
        for ($i=0; $i<16;$i++){
            $teams[$i] = Team::withSlug($teamslugs[$i]);
            $tournament->addTeam($teams[$i]);
            $manager->persist($teams[$i]);
        }
        $teamChunks = array_chunk($teams,8);
        $divisionA = Division::withTeams($teamChunks[0]);
        $divisionB = Division::withTeams($teamChunks[1]);
        for($i=0; $i<8;$i++) {
            $resultA = Result::withTeam($teamChunks[0][$i]);
            $resultB = Result::withTeam($teamChunks[1][$i]);
            $manager->persist($resultA);
            $manager->persist($resultB);
            $divisionA->addResult($resultA);
            $divisionB->addResult($resultB);
            for($j=$i+1; $j<8; $j++) {
                $gameA = Game::withTeam($teamChunks[0][$i], $teamChunks[0][$j]);
                $gameB = Game::withTeam($teamChunks[1][$i], $teamChunks[1][$j]);
                $manager->persist($gameA);
                $manager->persist($gameB);
                $divisionA->addGame($gameA);
                $divisionB->addGame($gameB);
            }
        }
        $manager->persist($divisionA);
        $manager->persist($divisionB);
        $tournament->setDivisionA($divisionA);
        $tournament->setDivisionB($divisionB);

        $playoff = new Playoff();
        $playoffGames = [];
        for ($i=0; $i<7; $i++) {
            $playoffGames[$i] = new Game();
            $manager->persist($playoffGames[$i]);
        }
        $playoff->setQuarter1($playoffGames[0]);
        $playoff->setQuarter2($playoffGames[1]);
        $playoff->setQuarter3($playoffGames[2]);
        $playoff->setQuarter4($playoffGames[3]);
        $playoff->setHalf1($playoffGames[4]);
        $playoff->setHalf2($playoffGames[5]);
        $playoff->setFinal($playoffGames[6]);
        $manager->persist($playoff);
        $tournament->setPlayoff($playoff);

        $manager->persist($tournament);
        $manager->flush();
    }
}