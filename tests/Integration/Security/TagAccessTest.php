<?php

declare(strict_types=1);

namespace App\Tests\Integration\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TagAccessTest extends WebTestCase
{
    public function testNonAdminCannotAccessCreateTag(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tag/new');

        $this->assertResponseRedirects('/login');
    }

    public function testNonAdminCannotAccessEditTag(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tag/edit/1');

        $this->assertResponseRedirects('/login');
    }

    public function testNonAdminCannotDeleteTag(): void
    {
        $client = static::createClient();
        $client->request('POST', '/tag/delete/1');

        $this->assertResponseRedirects('/login');
    }
}
