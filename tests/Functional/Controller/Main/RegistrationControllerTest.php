<?php

namespace App\Tests\Functional\Controller\Main;

use App\Repository\UserRepository;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

#[Group('functional')]
class RegistrationControllerTest extends WebTestCase
{
    public function testRegistration(): void
    {
        $client = static::createClient();
        $newUserEmail = 'new_test_user_1@gmail.com';
        $newUserPassword = 'test123';

        $client->request('GET', '/en/registration');
        $client->submitForm('SIGN UP', [
            'registration_form[email]' => $newUserEmail,
            'registration_form[plainPassword]' => $newUserPassword,
            'registration_form[agreeTerms]' => true,
        ]);

        $this->assertResponseRedirects('/en/',
            Response::HTTP_FOUND
        );

        $client->followRedirect();
        $this->assertSelectorTextContains('div', 'An email has been sent to your registered email address.');

        $user =  self::getContainer()->get(UserRepository::class)->findOneBy(['email' => $newUserEmail]);
        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());

        $transport =  self::getContainer()->get('messenger.transport.async');
        $this->assertCount(1, $transport->get());
    }

    public function testRegistrationEmailDuplicate(): void
    {
        $client = static::createClient();
        $newUserEmail = UserFixtures::USER_1_EMAIL;
        $newUserPassword = 'test123';

        $client->request('GET', '/en/registration');
        $client->submitForm('SIGN UP', [
            'registration_form[email]' => $newUserEmail,
            'registration_form[plainPassword]' => $newUserPassword,
            'registration_form[agreeTerms]' => true,
        ]);

        //$this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div', 'There is already an account with this email');
    }

}
