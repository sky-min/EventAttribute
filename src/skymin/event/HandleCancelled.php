<?php
declare(strict_types = 1);

namespace skymin\event;

use Attribute;
use function trigger_error;

#[Attribute(Attribute::TARGET_METHOD)]
final class HandleCancelled{

	public function __construct(private bool $handleCancelled = true){}

	public function __get(string $key) : bool{
		if($key === 'handleCancelled'){
			return $this->handleCancelled;
		}
		trigger_error("Undefined property $name or method $method_name");
	}

}