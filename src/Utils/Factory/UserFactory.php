<?php

namespace App\Utils\Factory;

use App\Entity\User;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;

class UserFactory
{
    public static function createUserFromFacebookUser(FacebookUser $facebookUser): User
    {
        return (new User())
            ->setEmail($facebookUser->getEmail())
            //->setFullName($facebookUser->getFirstName() . ' ' . $facebookUser->getLastName());
            ->setFullName($facebookUser->getName())
            ->setFacebookId($facebookUser->getId())
            ->setIsVerified(true);
    }

    public static function createUserFromGoogleUser(GoogleUser $googleUser): User
    {
        return (new User())
            ->setEmail($googleUser->getEmail())
            //->setFullName($googleUser->getFirstName() . ' ' . $googleUser->getLastName());
            ->setFullName($googleUser->getName())
            ->setGoogleId($googleUser->getId())
            ->setIsVerified(true);
    }

}
