<?php

namespace App\DataFixtures;

use App\Entity\Card;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CardFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create 52 cards
        $colors = Card::COLORS;
        $values = Card::VALUES;

        foreach ($colors as $color) {
            foreach ($values as $value) {
                $card = new Card();
                $card->setColor($color);
                $card->setValue($value);
                $manager->persist($card);
            }
        }

        $manager->flush();
    }
}
