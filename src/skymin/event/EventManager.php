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

use pocketmine\Server;
use pocketmine\plugin\{
	Plugin,
	PluginManager,
	PluginException
};
use pocketmine\event\{
	Event,
	Listener,
	EventPriority,
	Cancellable
};
use \ReflectionClass;
use \ReflectionMethod;

use function is_a;

final class EventManager{

	private static ?PluginManager $pluginmanager = null;

	private function __construct(){
		//Noop
	}

	public static function register(Listener $listener, Plugin $plugin) : void{
		if(!$plugin->isEnabled()){
			throw new PluginException('Plugin attempted to register ' . $listener::class . ' while not enabled');
		}
		if(self::$pluginmanager === null){
			self::$pluginmanager = Server::getInstance()->getPluginManager();
		}
		$pluginmanager = self::$pluginmanager;
		$ref = new ReflectionClass($listener::class);
		foreach($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method){
			$priority = EventPriority::NORMAL;
			$handleCancelled = false;
			$eventClass = self::getEventsHandledBy($method);
			if($eventClass === null){
				continue;
			}
			foreach($method->getAttributes() as $attribute){
				$attributeName = $attribute->getName();
				if($attributeName === NotHandler::class){
					continue 2;
				}if($attributeName === HandleCancelled::class){
					if(!is_a($eventClass, Cancellable::class, true)){
						throw new PluginException('non-cancellable event of type' . $eventClass);
					}
					$handleCancelled = $attribute->newInstance()->handleCancelled;
				}elseif($attributeName === Priority::class){
					$priority = $attribute->newInstance()->priority;
				}
			}
			$pluginmanager->registerEvent($eventClass, $method->getClosure($listener), $priority, $plugin,  $handleCancelled);
		}
	}

	private static function getEventsHandledBy(ReflectionMethod $method) : ?string{
		if($method->isStatic() || !$method->getDeclaringClass()->implementsInterface(Listener::class)){
			return null;
		}
		$parameters = $method->getParameters();
		if(count($parameters) !== 1){
			return null;
		}
		$paramType = $parameters[0]->getType();
		if(!$paramType instanceof \ReflectionNamedType || $paramType->isBuiltin()){
			return null;
		}
		$paramClass = $paramType->getName();
		$eventClass = new ReflectionClass($paramClass);
		if(!$eventClass->isSubclassOf(Event::class)){
			return null;
		}
		return $eventClass->getName();
	}

}