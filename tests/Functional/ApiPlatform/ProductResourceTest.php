<?php

namespace App\Tests\Functional\ApiPlatform;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

#[Group('functional')]
class ProductResourceTest extends ResourceTestUtils
{

    protected string $urikey = '/api/products';

    public function testGetProducts(): void
    {
        $client = static::createClient();
        $client->request('GET', $this->urikey, [], [], self::REQUEST_HEADERS);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetProduct(): void
    {
        $client = static::createClient();
        $product =  self::getContainer()->get(ProductRepository::class)->findOneBy([]);

        $uri = $this->urikey.'/'.$product->getId();
        $client->request('GET', $uri, [], [], self::REQUEST_HEADERS);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

   /* public function testCreateProduct(): void
    {
        $client = static::createClient();

        $this->checkDefaultUserHasNotAccess($client, $this->urikey, 'POST');

        $user =  self::getContainer()->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user,'main');
        $context = [
            'title' => 'New Product',
            'price' => 100,
            'quantity' => 5
        ];

        $client->request('POST', $this->urikey, [], [], self::REQUEST_HEADERS, json_encode($context));
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testPatchProduct(): void
    {
        $client = static::createClient();
        $product =  self::getContainer()->get(ProductRepository::class)->findOneBy([]);
        $uri = $this->urikey.'/'.$product->getUuid();
        $this->checkDefaultUserHasNotAccess($client, $uri, 'PATCH');

        $user =  self::getContainer()->get(UserRepository::class)->findOneBy(['email' => UserFixtures::USER_ADMIN_1_EMAIL]);

        $client->loginUser($user,'main');
        $context = [
            'title' => 'Updated Product',
        ];

        $client->request('PATCH', $uri, [], [], self::REQUEST_HEADERS_PATCH, json_encode($context));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }*/
}
