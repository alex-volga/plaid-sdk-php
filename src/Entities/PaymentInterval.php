<?php

namespace TomorrowIdeas\Plaid\Entities;

enum PaymentInterval: string
{
	case Weekly = "WEEKLY";
	case Monthly = "MONTHLY";
}
