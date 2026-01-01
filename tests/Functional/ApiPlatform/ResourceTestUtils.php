<?php

namespace App\Tests\Functional\ApiPlatform;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

#[Group('functional')]
class ResourceTestUtils extends WebTestCase
{

    protected string $urikey = '';

    protected const REQUEST_HEADERS = [
        'HTTP_ACCEPT' => 'application/ld+json',
        'CONTENT_TYPE' => 'application/json'
    ];

    protected const REQUEST_HEADERS_PATCH = [
        'HTTP_ACCEPT' => 'application/ld+json',
        'CONTENT_TYPE' => 'application/merge-patch+json'
    ];


    protected function checkDefaultUserHasNotAccess(AbstractBrowser $client, string $uri, string $method)
    {
        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $client->loginUser($user,'main');
        $client->request($method, $uri, [], [], self::REQUEST_HEADERS, json_encode([]));
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    protected function getResponseDecodedContent(AbstractBrowser $client)
    {
        return json_decode($client->getResponse()->getContent());
    }
}
