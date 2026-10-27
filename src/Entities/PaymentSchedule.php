<?php

namespace TomorrowIdeas\Plaid\Entities;

use DateTimeInterface;
use InvalidArgumentException;

class PaymentSchedule
{
	const INTERVAL_WEEKLY = "WEEKLY";
	const INTERVAL_MONTHLY = "MONTHLY";

	/**
	 * Payment schedule interval.
	 *
	 * @var string
	 */
	public readonly string $interval;

	/**
	 * Payment schedule execution day.
	 *
	 * @var int
	 */
	public readonly int $interval_execution_day;

	/**
	 * Start date of scheduled payments.
	 *
	 * @var DateTimeInterface
	 */
	public readonly DateTimeInterface $start_date;

	/**
	 * @param PaymentInterval|string $interval You can use PaymentInterval enum or constants PaymentSchedule::INTERVAL_WEEKLY and PaymentSchedule::INTERVAL_MONTHLY.
	 * @param integer $interval_execution_day
	 * @param DateTimeInterface $start_date
	 * @throws InvalidArgumentException
	 */
	public function __construct(
		PaymentInterval|string $interval,
		int $interval_execution_day,
		DateTimeInterface $start_date)
	{
		$intervalValue = $interval instanceof PaymentInterval ? $interval->value : $interval;

		if( !\in_array($intervalValue, [self::INTERVAL_MONTHLY, self::INTERVAL_WEEKLY], true) ){
			throw new InvalidArgumentException("Interval must be WEEKLY or MONTHLY.");
		}

		$this->interval = $intervalValue;
		$this->interval_execution_day = $interval_execution_day;
		$this->start_date = $start_date;
	}

	/**
	 * Get the payment schedule interval.
	 *
	 * @return string
	 */
	public function getInterval(): string
	{
		return $this->interval;
	}

	/**
	 * Get the interval execution day.
	 *
	 * @return integer
	 */
	public function getIntervalExecutionDay(): int
	{
		return $this->interval_execution_day;
	}

	/**
	 * Get the start date of the scheduled payment.
	 *
	 * @return DateTimeInterface
	 */
	public function getStartDate(): DateTimeInterface
	{
		return $this->start_date;
	}
}