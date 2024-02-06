<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->filter('.btn.btn-primary.btn-lgz');
        $this->assertEquals(1, count($button));

        $restaurants = $crawler->filter('.restaurants .card');
        $this->assertEquals(3, count($restaurants));

        $this->assertSelectorTextContains('h1', 'Bienvenue sur EatYourWay');
    }
}
