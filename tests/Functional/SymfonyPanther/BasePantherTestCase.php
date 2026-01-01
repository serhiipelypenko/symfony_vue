<?php

namespace App\Tests\Functional\SymfonyPanther;

use App\Repository\UserRepository;
use App\Tests\TestUtils\Fixtures\UserFixtures;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;


class BasePantherTestCase extends PantherTestCase
{

    protected function takeScreenshot(Client $client, string $filename)
    {
        $client->takeScreenshot('var/test-screenshots/'.$filename.'.png');
    }
    protected function initSeleniumClient(): Client
    {
        self::createPantherClient();
        self::startWebServer();

        $capabilities = [];
        return  Client::createSeleniumClient('http://127.0.0.1:4444/wd/hub', $capabilities,'http://127.0.0.1:9080');
    }

    private function getChromeCapabilities(): DesiredCapabilities
    {
        $chromeOptions = $this->getChromeOptions();
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);
        return $capabilities;
    }

    private function getChromeOptions(): ChromeOptions
    {
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments([
            '--window-size=1920,1080',
            '--headless'
        ]);
        return $chromeOptions;
    }
}

