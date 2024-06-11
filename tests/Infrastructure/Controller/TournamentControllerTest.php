<?php

namespace App\Tests\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TournamentControllerTest extends WebTestCase
{
    public function testCreateTournament(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/tournaments', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'title' => 'Roland Garros',
            'date' => '2024-06-10',
            'genderId' => 1
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetTournaments(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/tournaments');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGenerateGames(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/tournaments/1/generate-games', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'playerIds' => [1, 2, 3, 4, 5, 6, 7, 8]
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetTournament(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/tournaments/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    }
}
