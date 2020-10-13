## symfony 5 LDAP

I was encountering difficulties to work locally with ldap, with that project you can get a good starter to work with a local ldap based on docker containers.

## About

This POC demonstrates how to handle a LDAP authentication in symfony 5.

A custom user has been created in order to authenticate users against LDAP and return the matching `App\Entity\User` based on the username criteria.

If authentication is ok but there is no existing `App\Entity\User`, the custom user provider will instantiate one and fill it properties from the ldap.

## What's inside?

* webserver container
* database container
* ldap container
* phpldapadmin  container

See [docker-compose.yml](docker-compose.yml) for details.

## Usage

### Installation

    git clone git@github.com:benIT/symfony5-ldap-poc.git
    docker-compose up -d
    docker exec symfony5-ldap-poc_web_1 bash install.sh

### URLs

* web app: http://localhost:8089/
* php ldap admin: http://localhost:8080. admin credentials: `cn=admin,dc=mycorp,dc=com`/`s3cr3tpassw0rd` )
        

### Credentials

    user1/123
    user2/123
    ...

## Resources

* https://medium.com/@BiigDigital/docker-ldap-et-symfony-52173fa94bf4
* https://www.wanadev.fr/107-kit-de-survie-connexion-avec-le-composant-ldap-de-symfony/
* https://wiki.jordan-lenuff.com/Technique/Symfony/Connexion_LDAP_ou_AD
* https://alvinbunk.wordpress.com/2017/09/07/symfony-ldap-component-ad-authentication/
* https://stackoverflow.com/questions/64280314/symfony-5-ldap-authentication-with-custom-user-entity

