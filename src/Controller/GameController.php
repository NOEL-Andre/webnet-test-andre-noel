<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Game;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/start', name: 'start_game')]
    public function new(ManagerRegistry $doctrine, Request $request): Response
    {
        $game = new Game();

        $form = $this->createFormBuilder($game)
            ->add('cardNumber', IntegerType::class)
            ->add('start', SubmitType::class, ['label' => 'Create Game'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();

            $colors = Card::COLORS;
            $values = Card::VALUES;
            shuffle($colors);
            shuffle($values);

            $game->setColorOrder($colors);
            $game->setValueOrder($values);

            $em = $doctrine->getManager();
            $em->persist($game);
            $em->flush();

            return $this->redirectToRoute('play_game', ['game' => $game->getId()]);
        }

        return $this->render('start_game/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/play/{game}', name: 'play_game')]
    public function play(int $game, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $game = $em->getRepository(Game::class)->find($game);

        $numberOfCards = $game->getCardNumber();

        $cards = $em->getRepository(Card::class)->findAll();
        shuffle($cards);

        $hand = array_slice($cards, 0, $numberOfCards);

        $gameColorOrder = $game->getColorOrder();
        $gameValueOrder = $game->getValueOrder();

        $orderedHand = $hand;

        // sort array of cards by color defined in game
        usort(
            $orderedHand,
            static function (Card $a, Card $b) use ($gameColorOrder, $gameValueOrder) {
                $aColor = $a->getColor();
                $bColor = $b->getColor();
                $aValue = $a->getValue();
                $bValue = $b->getValue();

                $aColorIndex = array_search($aColor, $gameColorOrder);
                $bColorIndex = array_search($bColor, $gameColorOrder);
                $aValueIndex = array_search($aValue, $gameValueOrder);
                $bValueIndex = array_search($bValue, $gameValueOrder);

                if ($aColorIndex === $bColorIndex) {
                    return $aValueIndex <=> $bValueIndex;
                }

                return $aColorIndex <=> $bColorIndex;
            }
        );

        return $this->render('play_game/index.html.twig', [
            'game' => $game,
            'hand' => $hand,
            'orderedHand' => $orderedHand,
        ]);
    }
}
