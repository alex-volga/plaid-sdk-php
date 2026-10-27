<?php

namespace TomorrowIdeas\Plaid;

use Psr\Http\Message\ResponseInterface;

class PlaidRequestException extends PlaidException
{
	/**
	 * Get the response payload.
	 *
	 * @var object|null
	 */
	protected $response;

	/**
	 * PlaidRequestException constructor.
	 *
	 * @param ResponseInterface $responseInterface
	 */
	public function __construct(ResponseInterface $responseInterface)
	{
		$body = (string) $responseInterface->getBody();
		$response = \json_decode($body);

		$message = $responseInterface->getReasonPhrase();

		if( \is_object($response) ){
			$this->response = $response;
			$message = (string) ($this->response->error_message ?? $this->response->display_message ?? $message);
		}

		parent::__construct($message, $responseInterface->getStatusCode());
	}

	/**
	 * Get the Plaid specific error payload.
	 *
	 * @return object|null
	 */
	public function getResponse(): ?object
	{
		return $this->response;
	}

	/**
	 * Get Plaid error_code if present.
	 *
	 * @return string|null
	 */
	public function getErrorCode(): ?string
	{
		return $this->response->error_code ?? null;
	}

	/**
	 * Get Plaid error_type if present.
	 *
	 * @return string|null
	 */
	public function getErrorType(): ?string
	{
		return $this->response->error_type ?? null;
	}

	/**
	 * Get Plaid error_message if present.
	 *
	 * @return string|null
	 */
	public function getErrorMessage(): ?string
	{
		return $this->response->error_message ?? null;
	}

	/**
	 * Get Plaid display_message if present.
	 *
	 * @return string|null
	 */
	public function getDisplayMessage(): ?string
	{
		return $this->response->display_message ?? null;
	}

	/**
	 * Get Plaid request_id if present.
	 *
	 * @return string|null
	 */
	public function getRequestId(): ?string
	{
		return $this->response->request_id ?? null;
	}

	/**
	 * Get Plaid suggested_action if present.
	 *
	 * @return string|null
	 */
	public function getSuggestedAction(): ?string
	{
		return $this->response->suggested_action ?? null;
	}
}