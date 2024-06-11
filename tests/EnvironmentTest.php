<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnvironmentTest extends KernelTestCase
{
    public function testEnvTestLocalIsLoaded()
    {
        self::bootKernel();
        $testVariable = $_ENV['TEST_VARIABLE'] ?? null;
        $this->assertSame('env_test_loaded', $testVariable);
    }
}
