<?php

namespace tschrock\InvSee;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

/**
 * The main plugin class.
 */
class InvSee extends PluginBase {

    public function onLoad() {
        
    }

    public function onEnable() {
        
    }

    public function onDisable() {
        
    }

    private $originalInvs = [];

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        switch ($command->getName()) {
            case "invsee":
                if ($sender instanceof \pocketmine\Player) {
                    if (count($args) === 0 || $args[0] == "clear") {
                        if (isset($this->originalInvs[$sender->getId()])) {
                            $sender->getInventory()->setContents($this->originalInvs[$sender->getId()]);
                            unset($this->originalInvs[$sender->getId()]);
                        }
                        else {
                        $sender->sendMessage("Usage: /invsee <player>   or   /invsee clear");
                        }
                    } else {
                        if (!isset($this->originalInvs[$sender->getId()])) {
                            $player = $this->getServer()->getPlayerExact(array_shift($args));
                            if ($player !== null) {
                                $this->originalInvs[$sender->getId()] = $sender->getInventory()->getContents();
                                $sender->getInventory()->setContents($player->getInventory()->getContents());
                            } else {
                                $sender->sendMessage("That player doesn't exist or isn't online!");
                            }
                        } else {
                            $sender->sendMessage("You are already looking at a player's inventory. Use `/invsee` to stop looking.");
                        }
                    }
                } else {
                    if (count($args) === 0) {
                        $sender->sendMessage("Usage: /invsee <player>");
                    } else {
                        $player = $this->getServer()->getPlayerExact(array_shift($args));
                        if ($player !== null) {
                            $contents = $player->getInventory()->getContents();
                            foreach ($contents as $item) {
                                $sender->sendMessage($item->getCount() . " " . $item->getName() . " (" . $item->getId() . ":" . $item->getDamage() . ")");
                            }
                        } else {
                                $sender->sendMessage("That player doesn't exist or isn't online!");
                        }
                    }
                }
                return true;
            default:
                return false;
        }
    }

}
