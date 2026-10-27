<?php

namespace TomorrowIdeas\Plaid\Resources;

use Nimbly\Capsule\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TomorrowIdeas\Plaid\Plaid;
use TomorrowIdeas\Plaid\PlaidRequestException;
use UnexpectedValueException;

abstract class AbstractResource
{
	/**
	 * ClientInterface instance.
	 *
	 * @var ClientInterface
	 */
	protected readonly ClientInterface $httpClient;

	/**
	 * Plaid client Id.
	 *
	 * @var string
	 */
	private readonly string $client_id;

	/**
	 * Plaid client secret.
	 *
	 * @var string
	 */
	private readonly string $client_secret;

	/**
	 * Plaid hostname to use.
	 *
	 * @var string
	 */
	private readonly string $hostname;

	/**
	 * @param ClientInterface $httpClient
	 * @param string $client_id
	 * @param string $client_secret
	 * @param string $hostname
	 */
	public function __construct(
		ClientInterface $httpClient,
		string $client_id,
		string $client_secret,
		string $hostname)
	{
		$this->httpClient = $httpClient;
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->hostname = $hostname;
	}

	/**
	 * Build request body with client credentials.
	 *
	 * @param array<array-key,mixed> $params
	 * @return array
	 */
	protected function paramsWithClientCredentials(array $params = []): array
	{
		return \array_merge([
			"client_id" => $this->client_id,
			"secret" => $this->client_secret
		], $params);
	}

	/**
	 * Send a request and parse the response.
	 *
	 * @param string $method
	 * @param string $path
	 * @param array<array-key,mixed> $params
	 * @throws PlaidRequestException
	 * @throws UnexpectedValueException
	 * @return object
	 */
	protected function sendRequest(string $method, string $path, array $params = []): object
	{
		$response = $this->sendRequestRawResponse($method, $path, $params);

		$payload = \json_decode((string) $response->getBody());

		if( \json_last_error() !== JSON_ERROR_NONE ){
			throw new UnexpectedValueException("Invalid JSON response returned by Plaid");
		}

		return (object) $payload;
	}

	/**
	 * Make an HTTP request and get back the ResponseInterface instance.
	 *
	 * @param string $method
	 * @param string $path
	 * @param array<array-key,mixed> $params
	 * @throws PlaidRequestException
	 * @return ResponseInterface
	 */
	protected function sendRequestRawResponse(string $method, string $path, array $params = []): ResponseInterface
	{
		$response = $this->httpClient->sendRequest(
			$this->buildRequest($method, $path, $params)
		);

		if( $response->getStatusCode() < 200 || $response->getStatusCode() >= 300 ){
			throw new PlaidRequestException($response);
		}

		return $response;
	}

	/**
	 * Build the RequestInterface instance to be sent by the HttpClientInterface instance.
	 *
	 * @param string $method
	 * @param string $path
	 * @param array<array-key,mixed> $params
	 * @return RequestInterface
	 */
	protected function buildRequest(string $method, string $path, array $params = []): RequestInterface
	{
		$body = \json_encode((object) $params);
		if( $body === false ){
			throw new UnexpectedValueException("Failed to JSON encode request params: " . \json_last_error_msg());
		}

		return new Request(
			$method,
			$this->hostname . \trim($path, "/"),
			$body,
			[
				"Plaid-Version" => Plaid::API_VERSION,
				"Content-Type" => "application/json"
			]
		);
	}
}