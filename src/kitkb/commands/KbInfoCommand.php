<?php

declare(strict_types=1);

namespace kitkb\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use kitkb\KitKb;
use pocketmine\utils\TextFormat;

class KbInfoCommand extends Command
{

    public function __construct()
    {
        parent::__construct("kbinfo", "Displays the knockback information of a given kit.", "Usage: /kbinfo <name>", ["kitkbinfo"]);
        parent::setPermission("permission.kit.kbinfo");
    }

     /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param string[] $args
     *
     * @return mixed
     */
    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if($this->testPermission($sender))
        {
            if(!isset($args[0]))
            {
                $sender->sendMessage($this->getUsage());
                return true;
            }

            $kitName = $args[0];
            $kitManager = KitKb::getKitHandler();
            if(!$kitManager->isKit($kitName))
            {
                $sender->sendMessage(TextFormat::RED . "Unable to gather info. Reason: The kit doesn't exist.");
                return true;
            }

            $kbInfo = $kitManager->getKit($kitName)->getKbInfo();
            $sender->sendMessage($kbInfo->display());
        }

        return true;
    }
}