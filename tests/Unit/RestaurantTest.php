<?php

namespace App\Tests\Unit;

use App\Entity\Restaurant;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RestaurantTest extends KernelTestCase
{
    public function getEntity(): Restaurant
    {
        return (new Restaurant())
            ->setName('Restaurant #1')
            ->setDescription('Description #1')
            ->setIsFavorite(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $restaurant = $this->getEntity();

        $errors = $container->get('validator')->validate($restaurant);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $restaurant = $this->getEntity();
        $restaurant->setName('');

        $errors = $container->get('validator')->validate($restaurant);
        $this->assertCount(2, $errors);
    }

    
}
