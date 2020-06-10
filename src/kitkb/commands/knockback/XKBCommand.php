<?php

declare(strict_types=1);

namespace kitkb\commands\knockback;

use pocketmine\command\CommandSender;
use kitkb\KitKb;
use pocketmine\utils\TextFormat;

class XKBCommand extends KnockbackCommand
{

    public function __construct()
    {
        parent::__construct(KitKb::KB_X, "Command to edit the x-kb of the kit.");
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
        if(!$this->canExecute($sender, $args))
        {
            return true;
        }

        $kitManager = KitKb::getKitHandler();
        $kit = $kitManager->getKit($args[0]);
        $value = (float)$args[1];

        $kit->getKbInfo()->update(KitKb::KB_X, $value);
        $kitManager->update($kit);

        $sender->sendMessage(TextFormat::GREEN . " Successfully updated the x-kb of the kit.")

        return true;
    }
}