<?php

namespace Tests\Unit\Models;

use App\Models\AllowedIp;
use PHPUnit\Framework\TestCase;

class AllowedIpTest extends TestCase
{
    /**
     * A basic unit test to check if AllowedIp model can be instantiated.
     */
    public function test_allowed_ip_can_be_instantiated(): void
    {
        $allowedIp = new AllowedIp([
            'ip_address' => '192.168.1.1',
            'description' => 'Test IP'
        ]);

        $this->assertInstanceOf(AllowedIp::class, $allowedIp);
        $this->assertEquals('192.168.1.1', $allowedIp->ip_address);
        $this->assertEquals('Test IP', $allowedIp->description);
    }
}
