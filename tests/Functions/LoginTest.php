<?php

namespace App\Tests\Functions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        
        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security.login'));

        $form = $crawler->filter("form[name=login]")->form(["_username" => "admin@sharecipe.fr", 
        "_password" => "password"]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('home');

    }

    public function testIfLoginFailedWithWrongPassword (): void
    {
        $client = static::createClient();

        /**
         * @var UrlGeneratorInterface $urlGenerator
         */
        $urlGenerator = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $urlGenerator->generate('security.login'));

        $form = $crawler->filter("form[name=login]")->form([
            "_username" => "admin@sharecipe.fr",
            "_password" => "wrongpassword"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('security.login');

        $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
}
}
