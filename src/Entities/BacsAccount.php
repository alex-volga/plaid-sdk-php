<?php

namespace TomorrowIdeas\Plaid\Entities;

class BacsAccount
{
	/**
	 * Account number.
	 *
	 * @var string
	 */
	public readonly string $account;

	/**
	 * Sort code.
	 *
	 * @var string
	 */
	public readonly string $sort_code;

	public function __construct(
		string $account,
		string $sort_code
	)
	{
		$this->account = $account;
		$this->sort_code = $sort_code;
	}

	public function toArray(): array
	{
		return [
			"account" => $this->account,
			"sort_code" => $this->sort_code
		];
	}
}