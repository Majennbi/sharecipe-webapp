<?php

namespace App\Tests\Functions;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->filter('.nav-link:contains("S\'inscrire")');
        $this->assertEquals(1, count($button));

        $recipes = $crawler->filter('.recipes .card');
        $this->assertEquals(3, count($recipes));

        $this->assertSelectorTextContains('h1', 'Sharecipe');
    }
}
