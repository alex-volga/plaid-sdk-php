<?php

namespace TomorrowIdeas\Plaid;

enum Environment: string
{
	case Production = "production";
	case Development = "development";
	case Sandbox = "sandbox";
}
