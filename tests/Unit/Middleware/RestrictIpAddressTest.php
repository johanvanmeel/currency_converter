<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RestrictIpAddress;
use App\Models\AllowedIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RestrictIpAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_allows_access_when_no_allowed_ips_defined()
    {
        $middleware = new RestrictIpAddress();
        $request = Request::create('/admin/dashboard', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return new Response('Allowed');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }

    public function test_handle_denies_access_when_ip_is_not_allowed()
    {
        AllowedIp::create(['ip_address' => '192.168.1.1']);

        $middleware = new RestrictIpAddress();
        $request = Request::create('/admin/dashboard', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.2']);

        $response = $middleware->handle($request, function ($req) {
            return new Response('Allowed');
        });

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_handle_allows_access_when_ip_is_allowed()
    {
        AllowedIp::create(['ip_address' => '192.168.1.1']);

        $middleware = new RestrictIpAddress();
        $request = Request::create('/admin/dashboard', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

        $response = $middleware->handle($request, function ($req) {
            return new Response('Allowed');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }
}
