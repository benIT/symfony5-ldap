<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\LdapEncoder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Ldap\Ldap;
use Faker\Factory;

class LdapFixtures extends Fixture
{
    private $encoder;

    public function __construct(LdapEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $ldap = Ldap::create('ext_ldap', ['connection_string' => $_ENV['LDAP_URL']]);
        $ldap->bind($_ENV['LDAP_SEARCH_DN'], $_ENV['LDAP_SEARCH_PASSWORD']);
        $entryManager = $ldap->getEntryManager();
        $ou = new Entry('ou=People,dc=mycorp,dc=com', [
            'objectClass' => ['organizationalUnit'],
            'ou' => ['People']
        ]);
        $entryManager->add($ou);
        $plainPassword = '123';
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 100; $i++) {
            $uid = sprintf('user%d', $i);
            $firstName = strtolower($faker->firstName);
            $lastName = strtolower($faker->lastName);
            $dn = sprintf('uid=%s,ou=People,dc=mycorp,dc=com', $uid);
            $entry = new Entry($dn, [
                'objectClass' => [
                    'inetOrgPerson',
                ],
                'sn' => [$firstName],
                'uid' => [$uid],
                'cn' => [sprintf('%s %s', $firstName, $lastName)],
                'mail' => [sprintf('%s.%s@mail.com', $firstName, $lastName)],
                'userPassword' => [$this->encoder->encodePassword($plainPassword, null)]
            ]);
            $entryManager->add($entry);
        }
    }
}