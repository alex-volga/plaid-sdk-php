<?php

namespace TomorrowIdeas\Plaid\Resources;

use TomorrowIdeas\Plaid\PlaidRequestException;

class Identity extends AbstractResource
{
	/**
	 * Get Identity request.
	 *
	 * @param string $access_token
	 * @param array<string,mixed> $options
	 * @throws PlaidRequestException
	 * @return object
	 */
	public function get(string $access_token, array $options = []): object
	{
		$params = [
			"access_token" => $access_token,
			"options" => (object) $options
		];

		return $this->sendRequest(
			"post",
			"identity/get",
			$this->paramsWithClientCredentials($params)
		);
	}

	/**
	 * Match Identity request.
	 *
	 * @param string $access_token
	 * @param array<string,mixed> $user
	 * @param array<string,mixed> $options
	 * @throws PlaidRequestException
	 * @return object
	 */
	public function match(string $access_token, array $user, array $options = []): object
	{
		$params = [
			"access_token" => $access_token,
			"user" => (object) $user,
			"options" => (object) $options
		];

		return $this->sendRequest(
			"post",
			"identity/match",
			$this->paramsWithClientCredentials($params)
		);
	}
}
