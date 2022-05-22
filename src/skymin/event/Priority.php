<?php
/**
 *      _                    _       
 *  ___| | ___   _ _ __ ___ (_)_ __  
 * / __| |/ / | | | '_ ` _ \| | '_ \ 
 * \__ \   <| |_| | | | | | | | | | |
 * |___/_|\_\\__, |_| |_| |_|_|_| |_|
 *           |___/ 
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 * 
 * @author skymin
 * @link   https://github.com/sky-min
 * @license https://opensource.org/licenses/MIT MIT License
 * 
 *   /\___/\
 * 　(∩`・ω・)
 * ＿/_ミつ/￣￣￣/
 * 　　＼/＿＿＿/
 *
 */

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
		trigger_error('Undefined property' . $key);
	}

}