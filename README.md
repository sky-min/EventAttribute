# EventAttribute
pmmp event attribute

Attributes are superior to PhpDoc 

# Example
## Listener
```php
<?php
delcare(strict_types = 1);

namespace example;

use pocketmine\event\Listener;
use pocketmine\evenr\EventPriority;
use pocketmine\event\player\PlayerChatEvent;

use skymin\event\{Priority, HandleCancelled, NotHandler};

final class ExampleListener implements Listener{

    #[HandleCancelled]
    #[Priority(EventPriority::HIGHEST)]
    public function onChat(PlayerChatEvent $event) : void{
        //...
    }

    #[HandleCancelled, Priority(EventPriority::MONITOR)]
    public function doChat(PlayerChatEvent $event) : void{
        //...
    }

    #[NotHandler]
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