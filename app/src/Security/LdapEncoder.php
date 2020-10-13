<?php

namespace App\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LdapEncoder implements PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
        return "{SHA}" . base64_encode(pack("H*", sha1($raw)));
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        $rawEncoded = "{SHA}" . base64_encode(pack("H*", sha1($raw)));

        return $encoded === $rawEncoded;
    }

    public function needsRehash(string $encoded): bool
    {
        // TODO: Implement needsRehash() method.
    }
}