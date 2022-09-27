# EventAttribute
pmmp event attribute

Attributes are superior to PhpDoc 

# Example
## Listener
```php
<?php
delcare(strict_types = 1);

namespace example;

use pocketmine\evenr\EventPriority;
use pocketmine\event\player\PlayerChatEvent;

use skymin\event\EventHandler;

final class ExampleListener {

    #[EventHandler(EventPriority::HIGHEST)]
    public function onChat(PlayerChatEvent $event) : void{
        //...
    }

    #[EventHandler(
    	EventPriority::MONITOR,
    	true
    )]
    public function doChat(PlayerChatEvent $event) : void{
        //...
    }

	//Event is not registered without the EventHandler attribute.
    public function chat(PlayerChatEvent $event) : void{
        //...
    }

}
```

## Listener register
```php
<?php
delcare(strict_types = 1);

namespace example;

use pocketmine\plugin\PluginBase;

use skymin\event\EventManager;

final class ExampleLoader extends PluginBase{

	protected function onEnable() : void{
		EventManager::register(new ExampleListener(), $this);
	}

}
```
