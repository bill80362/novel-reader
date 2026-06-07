<?php

namespace Tests\Feature;

use Tests\TestCase;

class McpAuthTest extends TestCase
{
    public function test_mcp_endpoint_requires_bearer_token(): void
    {
        $response = $this->postJson('/mcp');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_mcp_endpoint_rejects_invalid_token(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/mcp');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_mcp_endpoint_accepts_valid_token(): void
    {
        $validToken = config('mcp.secret') ?? env('MCPAPP_SECRET');

        $response = $this->withHeader('Authorization', 'Bearer '.$validToken)
            ->postJson('/mcp', [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'ping',
            ]);

        // Should not be 401 (may be 200, 400, 500 depending on MCP implementation)
        $this->assertNotEquals(401, $response->getStatusCode());
    }
}
