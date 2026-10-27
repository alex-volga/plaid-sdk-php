<?php

namespace TomorrowIdeas\Plaid\Entities;

class AccountHolder
{
	/**
	 * Legal name of account holder.
	 *
	 * @var string
	 */
	public readonly string $legal_name;

	/**
	 * Email address of account holder.
	 *
	 * @var string|null
	 */
	public readonly ?string $email;

	public function __construct(
		string $legal_name,
		?string $email = null
	)
	{
		$this->legal_name = $legal_name;
		$this->email = $email;
	}

	public function toArray(): array
	{
		return [
			"legal_name" => $this->legal_name,
			"email" => $this->email
		];
	}
}