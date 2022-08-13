<?php

/**
 * @name World2PM4
 * @main PlanetServer\World2PM4\World2PM4
 * @author HelloWorld
 * @version 1.0.0
 * @api 4.0.0
 */

namespace PlanetServer\World2PM4;

use pocketmine\command\PluginCommand;
use pocketmine\permission\DefaultPermissions;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;


class World2PM4 extends PluginBase
{

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if(! $command->testPermission($sender))
            return false;
        $manager = $this->getServer()->getWorldManager();
        switch($command->getName()){
            case 'convert2pm4':
                $name = array_shift($args) ?? 'x';
                if($name === 'x'){
                    $sender->sendMessage($command->getName() . ' [world name]');
                    return false;
                }
                if($manager->isWorldLoaded($name)){
                    $sender->sendMessage('world ' . $name . ' is already loaded');
                    return false;
                }
                if(!$manager->loadWorld($name, true)){
                    $sender->sendMessage('cannot load level: ' . $name);
                    return false;
                }
                $sender->sendMessage("\n\n\nconverted and loaded successfully\n\n");
                break;
            case 'loadedworlds':
                $list = [];
                foreach($manager->getWorlds() as $world)
                    $list[] = $world->getDisplayName() . ' (folder: ' . $world->getFolderName() . ')';
                $sender->sendMessage('loaded worlds: ' . implode(', ', $list));
                break;
        }
        return true;
    }

    protected function onEnable(): void
    {
        foreach([
            'loadedworlds' => DefaultPermissions::ROOT_OPERATOR,
            'convert2pm4'  => DefaultPermissions::ROOT_OPERATOR
        ] as $commandName => $permission) {
            $command = new PluginCommand($commandName, $this, $this);
            $command->setPermission($permission);
            $this->getServer()->getCommandMap()->register($commandName, $command);
        }
    }

}