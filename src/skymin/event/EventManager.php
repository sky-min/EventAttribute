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
use ReflectionClass;
use ReflectionMethod;

final class EventManager{

	private function __construct(){
		//NOOP
	}

	public static function register(object $listener, Plugin $plugin) : void{
		if(!$plugin->isEnabled()){
			throw new PluginException('Plugin attempted to register ' . $listener::class . ' while not enabled');
		}
		static $pluginmanager = null;
		if($pluginmanager === null){
			$pluginmanager = Server::getInstance()->getPluginManager();
		}

		$ref = new ReflectionClass($listener::class);
		foreach($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method){
			if($method->isStatic()){
				continue;
			}

			$parameters = $method->getParameters();
			if(count($parameters) !== 1){
				continue;
			}
			$paramType = $parameters[0]->getType();
			if(!$paramType instanceof \ReflectionNamedType || $paramType->isBuiltin()){
				continue;
			}
			$eventClass = new ReflectionClass($paramType->getName());
			if(!$eventClass->isSubclassOf(Event::class)){
				continue;
			}

			$eventClassName = $eventClass->getName();

			foreach($method->getAttributes() as $attribute){
				if($attribute->getName() !== EventHandler::class){
					continue;
				}
				$handler = $attribute->newInstance();
				$handleCancelled = $handler->isHandleCancelled();
				if($handleCancelled && !$eventClass->isSubclassOf(Cancellable::class)){
					throw new PluginException('non-cancellable event of type' . $eventClassName);
				}
				$pluginmanager->registerEvent(
					$eventClassName,
					$method->getClosure($listener),
					$handler->getPriority(),
					$plugin,
					$handleCancelled
				);
			}
		}
	}

}