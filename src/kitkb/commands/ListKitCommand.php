<?php

declare(strict_types=1);

namespace kitkb\commands;


use kitkb\KitKb;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ListKitCommand extends Command
{

    public function __construct()
    {
        parent::__construct("listKits", "Lists all of the kits.", "Usage: /listKits", ["kitlist", "listkits"]);
        parent::setPermission("permission.kit.list");
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
            $kits = KitKb::getKitHandler()->getKits();
            $message = TextFormat::GOLD . "Kits: " . TextFormat::WHITE;
            if(count($kits) <= 0)
            {
                $sender->sendMessage($message . "None");
                return true;
            }

            $kitExtension = [];
            foreach($kits as $kit)
            {
                $kitExtension[] = $kit->getName();
            }
            $sender->sendMessage($message . implode(", ", $kitExtension));
        }

        return true;
    }
}