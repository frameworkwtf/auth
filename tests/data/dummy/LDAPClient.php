<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Dummy;

class LDAPClient extends \Wtf\Root
{
    public function bind($dn, $password)
    {
        if ('cn=exists,cn=Users,dc=framework,dc=wtf' === $dn && 'valid' === $password) {
            return true;
        }

        throw new \Exception('cant bind');
    }

    public function query($baseDN, $query)
    {
        return new class($baseDN, $query) {
            public function __construct($baseDN, $query)
            {
                $this->baseDN = $baseDN;
                $this->query = $query;
            }

            public function execute()
            {
                return new class($this->baseDN, $this->query) {
                    public function __construct($baseDN, $query)
                    {
                        $this->baseDN = $baseDN;
                        $this->query = $query;
                    }

                    public function toArray()
                    {
                        if ('(|(uid=exists)(mail=exists))' === $this->query) {
                            return [new \Symfony\Component\Ldap\Entry('cn=exists,cn=Users,dc=framework,dc=wtf', [
                                'dn' => 'cn=exists,cn=Users,dc=framework,dc=wtf',
                                'cn' => 'User exists',
                            ])];
                        }

                        return [];
                    }
                };
            }
        };
    }
}
