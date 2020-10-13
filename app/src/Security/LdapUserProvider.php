<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LdapUserProvider implements UserProviderInterface
{
    /**
     * @var Ldap
     */
    private $ldap;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var string
     */
    private $ldapSearchDn;
    /**
     * @var string
     */
    private $ldapSearchPassword;
    /**
     * @var string
     */
    private $ldapBaseDn;
    /**
     * @var string
     */
    private $ldapSearchDnString;


    public function __construct(EntityManagerInterface $entityManager, Ldap $ldap, string $ldapSearchDn, string $ldapSearchPassword, string $ldapBaseDn, string $ldapSearchDnString)
    {
        $this->ldap = $ldap;
        $this->entityManager = $entityManager;
        $this->ldapSearchDn = $ldapSearchDn;
        $this->ldapSearchPassword = $ldapSearchPassword;
        $this->ldapBaseDn = $ldapBaseDn;
        $this->ldapSearchDnString = $ldapSearchDnString;
    }

    /**
     * @param string $username
     * @return UserInterface|void
     * @see getUserEntityCheckedFromLdap(string $username, string $password)
     */
    public function loadUserByUsername($username)
    {
        // must be present because UserProviders must implement UserProviderInterface
    }

    /**
     * search user against ldap and returns the matching App\Entity\User. The $user entity will be created if not exists.
     * @param string $username
     * @param string $password
     * @return User|object|null
     */
    public function getUserEntityCheckedFromLdap(string $username, string $password)
    {
        $this->ldap->bind(sprintf($this->ldapSearchDnString, $username), $password);
        $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_FILTER);
        $search = $this->ldap->query($this->ldapBaseDn, 'uid=' . $username);
        $entries = $search->execute();
        $count = count($entries);
        if (!$count) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
        if ($count > 1) {
            throw new UsernameNotFoundException('More than one user found');
        }
        $ldapEntry = $entries[0];
        $userRepository = $this->entityManager->getRepository('App\Entity\User');
        if (!$user = $userRepository->findOneBy(['userName' => $username])) {
            $user = new User();
            $user->setUserName($username);
            $user->setEmail($ldapEntry->getAttribute('mail')[0]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $user;
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }
        return $user;

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        throw new \Exception('TODO: fill in refreshUser() inside ' . __FILE__);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}