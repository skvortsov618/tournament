<?php

namespace App\Controller;

use App\Entity\Division;
use App\Entity\Result;
use App\Entity\Tournament;
use App\Repository\DivisionRepository;
use App\Repository\GameRepository;
use App\Repository\PlayoffRepository;
use App\Repository\TournamentRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_index");
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/{id}", name="app_tournament");
     */
    public function tournament(
        Tournament $tournament,
        DivisionRepository $divisionRepository,
        PlayoffRepository $playoffRepository,
        GameRepository $gameRepository
    ): Response
    {
        function setRows(&$teams, $games, $division) {
            for ($i=0;$i<8;$i++) {
                $teams[$i]->games = [];
                for ($j = 0; $j < 8; $j++) {
                    if ($i==$j) {
                        $teams[$i]->games[$j]=null;
                    } else {
                        for ($k = 0; $k < count($games); $k++) {
                            $teamAID = $games[$k]->getTeamA()->getID();
                            $teamBID = $games[$k]->getTeamB()->getID();
                            if ($teamAID==$teams[$i]->getID() && $teamBID==$teams[$j]->getID()){
                                $teams[$i]->games[$j] = $games[$k];
                            } else if ($teamAID==$teams[$j]->getID() && $teamBID==$teams[$i]->getID())
                            {
                                $teams[$i]->games[$j] = $games[$k];
                            }
                        }
                    }
                }
                $teams[$i]->result = $division->getResult($teams[$i]);
            }
        }

        $tournamentName = $tournament->getTournamentName();
        $divisionA=$divisionRepository->find($tournament->getDivisionA()->getId());
        $teamsA=$divisionA->getTeams()->getValues();
        $gamesA=$divisionA->getGames()->getValues();
        setRows($teamsA, $gamesA, $divisionA);

        $divisionB=$divisionRepository->find($tournament->getDivisionB()->getId());
        $teamsB=$divisionB->getTeams()->getValues();
        $gamesB=$divisionB->getGames()->getValues();
        setRows($teamsB,$gamesB, $divisionB);

        $playoff = $playoffRepository->find($tournament->getPlayoff()->getId());
        $winner = "";
//        dd($tournament->getResults()->getValues());
        if ($tournament->getResults()->getValues()!= null) {
            $winner = $tournament->getResults()->getValues()[0]->getTeam()->getTeamName();
        }
//        $quarters[0] = $playoff->getQuarter1();
//        dd($quarters);

        return $this->render('main/tournament.html.twig', [
            'name' => $tournamentName,
            'id'=>$tournament->getId(),
            'teamsA' => $teamsA,
            'teamsB' => $teamsB,
            'playoff' => $playoff,
            'winner' => $winner
        ]);
    }

    /**
     * @Route("/playdivisions/{id}");
     */
    public function playdivisions($id,
        GameRepository $gameRepository,
        TournamentRepository $tournamentRepository,
        DivisionRepository $divisionRepository,
        ManagerRegistry $manager,
        PlayoffRepository $playoffRepository
    )
    {
        $manager = $manager->getManager();
        $tournament = $tournamentRepository->find($id);

        $divisionA=$divisionRepository->find($tournament->getDivisionA()->getId());
        $gamesA=$divisionA->getGames()->getValues();
        foreach ($gamesA as $game) {$game->autoplay();$manager->persist($game);}
        $divisionA->computeResults();
        foreach($divisionA->getResults()->getValues() as $result) {$manager->persist($result);}
        $manager->persist($divisionA);

        $divisionB=$divisionRepository->find($tournament->getDivisionB()->getId());
        $gamesB=$divisionB->getGames()->getValues();
        foreach ($gamesB as $game) {$game->autoplay();$manager->persist($game);}
        $divisionB->computeResults();
        foreach($divisionB->getResults()->getValues() as $result) {$manager->persist($result);}
        $manager->persist($divisionB);

        $playoff = $playoffRepository->find($tournament->getPlayoff()->getId());
        $playoffteams = $tournament->computePlayoffTeams();
        foreach ($playoffteams as $team) {$playoff->addTeam($team);}
        $playoff->setQuarters();
        $manager->persist($playoff);

        $manager->flush();
        return $this->redirectToRoute("app_tournament", ['id'=>$id]);
    }

    /**
     * @Route("/playquarters/{id}")
     */
    public function playQuarters($id,
         GameRepository $gameRepository,
         TournamentRepository $tournamentRepository,
         DivisionRepository $divisionRepository,
         ManagerRegistry $manager,
         PlayoffRepository $playoffRepository
    )
    {
        $manager = $manager->getManager();
        $tournament = $tournamentRepository->find($id);
        $playoff = $tournament->getPlayoff();
        $playoff->autoplayQuarters();
        $playoff->setHalfs();
        $manager->persist($playoff);

        $manager->flush();
        return $this->redirectToRoute("app_tournament", ['id'=>$id]);

    }

    /**
     * @Route("/playhalfs/{id}")
     */
    public function playHalfs($id,
         GameRepository $gameRepository,
         TournamentRepository $tournamentRepository,
         DivisionRepository $divisionRepository,
         ManagerRegistry $manager,
         PlayoffRepository $playoffRepository
    )
    {
        $manager = $manager->getManager();
        $tournament = $tournamentRepository->find($id);
        $playoff = $tournament->getPlayoff();
        $playoff->autoplayHalfs();
        $playoff->setPlayoffFinal();
        $manager->persist($playoff);

        $manager->flush();
        return $this->redirectToRoute("app_tournament", ['id'=>$id]);

    }

    /**
     * @Route("/playfinal/{id}")
     */
    public function playFinal($id,
          GameRepository $gameRepository,
          TournamentRepository $tournamentRepository,
          DivisionRepository $divisionRepository,
          ManagerRegistry $manager,
          PlayoffRepository $playoffRepository
    )
    {
        $manager = $manager->getManager();
        $tournament = $tournamentRepository->find($id);
        $playoff = $tournament->getPlayoff();
        $playoff->autoplayFinal();
        $winner = $playoff->computeResults();
        $winresult = Result::withTeamAndScore($winner, 10000);
        $manager->persist($winresult);
        $tournament->addResult($winresult);
        $manager->persist($tournament);
        $manager->persist($playoff);

        $manager->flush();
        return $this->redirectToRoute("app_tournament", ['id'=>$id]);

    }
}
