<?php

namespace TomorrowIdeas\Plaid\Tests;

use Nimbly\Capsule\Response;
use TomorrowIdeas\Plaid\PlaidRequestException;

/**
 * @covers TomorrowIdeas\Plaid\PlaidRequestException
 * @uses TomorrowIdeas\Plaid\PlaidException
 */
class PlaidRequestExceptionTest extends TestCase
{
	public function test_getting_code_from_exception(): void
	{
		$response = new Response(
			404,
			'{"display_message": "Foo not found"}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals(404, $plaidRequestException->getCode());
	}

	public function test_getting_display_message_on_exception(): void
	{
		$response = new Response(
			404,
			'{"display_message": "Foo not found"}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals("Foo not found", $plaidRequestException->getMessage());
	}

	public function test_getting_fallback_message(): void
	{
		$response = new Response(
			404,
			'{"error": "Foo not found"}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals("Not Found", $plaidRequestException->getMessage());
	}

	public function test_getting_fallback_message_for_unknown_payload(): void
	{
		$response = new Response(
			404,
			"Foo not found"
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals("Not Found", $plaidRequestException->getMessage());
	}

	public function test_getting_payload_from_exception(): void
	{
		$response = new Response(
			404,
			'{"display_message": "Foo not found"}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals(
			(object) ["display_message" => "Foo not found"],
			$plaidRequestException->getResponse()
		);
	}

	public function test_getting_error_message_precedence_over_display_message(): void
	{
		$response = new Response(
			400,
			'{"error_message": "Technical details", "display_message": "User friendly message"}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals("Technical details", $plaidRequestException->getMessage());
	}

	public function test_getter_helpers_return_plaid_fields(): void
	{
		$response = new Response(
			400,
			'{
				"error_type": "ITEM_ERROR",
				"error_code": "ITEM_LOGIN_REQUIRED",
				"error_message": "The login details of this item have changed",
				"display_message": "Please log in again",
				"request_id": "req_12345",
				"suggested_action": "RETRY_WITH_NEW_CREDENTIALS"
			}'
		);

		$plaidRequestException = new PlaidRequestException($response);

		$this->assertEquals("ITEM_ERROR", $plaidRequestException->getErrorType());
		$this->assertEquals("ITEM_LOGIN_REQUIRED", $plaidRequestException->getErrorCode());
		$this->assertEquals("The login details of this item have changed", $plaidRequestException->getErrorMessage());
		$this->assertEquals("Please log in again", $plaidRequestException->getDisplayMessage());
		$this->assertEquals("req_12345", $plaidRequestException->getRequestId());
		$this->assertEquals("RETRY_WITH_NEW_CREDENTIALS", $plaidRequestException->getSuggestedAction());
	}
}