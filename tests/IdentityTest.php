<?php

namespace TomorrowIdeas\Plaid\Tests;

/**
 * @covers TomorrowIdeas\Plaid\Plaid
 * @covers TomorrowIdeas\Plaid\Resources\AbstractResource
 * @covers TomorrowIdeas\Plaid\Resources\Identity
 */
class IdentityTest extends TestCase
{
	public function test_get_identity(): void
	{
		$response = $this->getPlaidClient()->identity->get("access_token");

		$this->assertEquals("POST", $response->method);
		$this->assertEquals("2020-09-14", $response->version);
		$this->assertEquals("application/json", $response->content);
		$this->assertEquals("/identity/get", $response->path);
		$this->assertEquals("client_id", $response->params->client_id);
		$this->assertEquals("secret", $response->params->secret);
		$this->assertEquals("access_token", $response->params->access_token);
		$this->assertEquals((object) [], $response->params->options);
	}

	public function test_get_identity_with_options(): void
	{
		$response = $this->getPlaidClient()->identity->get("access_token", ["account_ids" => ["acc_123"]]);

		$this->assertEquals("POST", $response->method);
		$this->assertEquals("/identity/get", $response->path);
		$this->assertEquals((object) ["account_ids" => ["acc_123"]], $response->params->options);
	}

	public function test_match_identity(): void
	{
		$user = [
			"legal_name" => "Jane Doe",
			"phone_number" => "+1 415 555 0123",
			"email_address" => "jane.doe@example.com"
		];

		$response = $this->getPlaidClient()->identity->match("access_token", $user);

		$this->assertEquals("POST", $response->method);
		$this->assertEquals("2020-09-14", $response->version);
		$this->assertEquals("application/json", $response->content);
		$this->assertEquals("/identity/match", $response->path);
		$this->assertEquals("client_id", $response->params->client_id);
		$this->assertEquals("secret", $response->params->secret);
		$this->assertEquals("access_token", $response->params->access_token);
		$this->assertEquals((object) $user, $response->params->user);
		$this->assertEquals((object) [], $response->params->options);
	}
}
