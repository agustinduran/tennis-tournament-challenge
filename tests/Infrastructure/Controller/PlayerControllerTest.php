<?php

namespace App\Tests\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    public function testCreatePlayer(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/players', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'fullName' => 'John Doe',
            'habilityLevel' => 5,
            'luckyLevel' => 3,
            'genderId' => 1
        ]));

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
