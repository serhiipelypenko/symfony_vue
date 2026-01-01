<?php

namespace App\Tests\Integration\Security\Verifier;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Security\Verifier\EmailVerifier;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use App\Utils\Manager\UserManager;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;

#[Group('integration')]
class EmailVerifierTest extends KernelTestCase
{

    /*
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /*
     * @var EmailVerifier
     */
    private EmailVerifier $emailVerifier;
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = self::getContainer();

        /** @var RouterInterface $router */
        $router = $container->get('router');
        $router->getContext()->setParameter('_locale', 'en');

        $this->userRepository = $container->get(UserRepository::class);
        $this->emailVerifier = $container->get(EmailVerifier::class);
        //$categoryRepository = $container->get(CategoryRepository::class);
        //$productRepository = $container->get(ProductRepository::class);

    }

    public function testGenerateEmailSignature(): void
    {
        $user = $this->userRepository->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $user->setIsVerified(false);

        $currentDateTime = new \DateTimeImmutable();
        $emailSignature = $this->emailVerifier->generateEmailSignature('main_verify_email',$user);

        $this->assertGreaterThan($currentDateTime, $emailSignature->getExpiresAt());
    }

    public function testHandleEmailConfirmation(): void
    {
        $user = $this->userRepository->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $user->setIsVerified(false);

        $currentDateTime = new \DateTimeImmutable();
        $emailSignature = $this->emailVerifier->generateEmailSignature('main_verify_email',$user);
        $signedUrl = $emailSignature->getSignedUrl();
        // Create a fake Request object with the signed URL as the URI
        $request = \Symfony\Component\HttpFoundation\Request::create($signedUrl);

        $this->emailVerifier->handleEmailConfirmation($request,$user);
        $this->assertTrue($user->isVerified());
    }

    public function testGenerateEmailSignatureAndHandleEmailConfirmation(){
        $user = $this->userRepository->findOneBy(['email' => UserFixtures::USER_1_EMAIL]);
        $user->setIsVerified(false);

        $emailSignature = $this->checkGenerateEmailSignature($user);
        $this->checkHandleEmailConfirmation($emailSignature,$user);
    }

    private function checkGenerateEmailSignature(User $user): VerifyEmailSignatureComponents
    {
        $currentDateTime = new \DateTimeImmutable();
        $emailSignature = $this->emailVerifier->generateEmailSignature('main_verify_email',$user);

        $this->assertGreaterThan($currentDateTime, $emailSignature->getExpiresAt());
        return $emailSignature;
    }

    private function checkHandleEmailConfirmation(VerifyEmailSignatureComponents $signatureComponents,
    User $user): void
    {
        $signedUrl = $signatureComponents->getSignedUrl();
        // Create a fake Request object with the signed URL as the URI
        $request = \Symfony\Component\HttpFoundation\Request::create($signedUrl);
        $this->emailVerifier->handleEmailConfirmation($request,$user);
        $this->assertTrue($user->isVerified());
    }
}
