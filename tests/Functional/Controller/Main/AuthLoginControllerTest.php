<?php

namespace App\Tests\Functional\Controller\Main;

use App\Repository\UserRepository;
use App\Tests\Functional\SymfonyPanther\BasePantherTestCase;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;


class AuthLoginControllerTest extends BasePantherTestCase
{
    #[Group('functional')]
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/en/login');
        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->assertResponseRedirects('/en/profile',
            Response::HTTP_FOUND
        );

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
    }

    #[Group('functional-selenium')]
    public function testLoginWithSeleniumClient(): void
    {

        $client = $this->initSeleniumClient();

        $client->request('GET', '/en/login');
        $crawler = $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->takeScreenshot($client,'auth-login-controller-test-login_1');

        $this->assertSame(
            $crawler->filter('h1')->text(),
            'Welcome, to your profile!');
    }

    #[Group('functional-panther')]
    public function testLoginWithPantherClient(): void
    {
        $client = static::createPantherClient(['browser'=>self::CHROME]);
        $client->request('GET', '/en/login');
        $client->submitForm('LOG IN', [
            'email' => UserFixtures::USER_1_EMAIL,
            'password' => UserFixtures::USER_1_PASSWORD,
        ]);

        $this->assertSame(self::$baseUri.'/en/profile', $client->getCurrentURL());
        $this->assertPageTitleContains('My profile - RankedChoice');
        $this->assertSelectorTextContains('#page_header_title', 'Welcome, to your profile!');


    }

}
