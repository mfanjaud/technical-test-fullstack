<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testSomething(): void
    {
        $addition = 5 + 2;
        $booleanValue = true;
        $array = ['key' => 'value'];

        $this->assertEquals($addition, 7, 'addition is expected to equal 7');
        $this->assertTrue($booleanValue, 'boolean should be truthy');
        $this->assertArrayHasKey('key', $array);
    }
}
