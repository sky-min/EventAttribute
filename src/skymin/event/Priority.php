<?php
declare(strict_types = 1);

namespace skymin\event;

use Attribute;
use pocketmine\event\EventPriority;

use function in_array;
use function trigger_error;

#[Attribute(Attribute::TARGET_METHOD)]
final class Priority{

	private int $priority;

	public function __construct(
		int $priority = EventPriority::NORMAL
	){
		if(!in_array($priority, EventPriority::ALL, true)){
			$priority = EventPriority::NORMAL;
		}
		$this->priority = $priority;
	}

	public function __get(string $key) : int{
		if($key === 'priority'){
			return $this->priority;
		}
		trigger_error("Undefined property $name or method $method_name");
	}

}