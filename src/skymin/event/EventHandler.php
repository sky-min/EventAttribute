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

#[Attribute(Attribute::TARGET_METHOD)]
final class EventHandler{

	private int $priority;

	public function __construct(
		int $priority = EventPriority::NORMAL,
		private bool $handleCancelled = false
	){
		if(!in_array($priority, EventPriority::ALL, true)){
            throw new \LogicException("Invalid event priority");
        }
		$this->priority = $priority;
	}

	public function getPriority() : int{
		return $this->priority;
	}

	public function isHandleCancelled() : bool{
		return $this->handleCancelled;
	}

}