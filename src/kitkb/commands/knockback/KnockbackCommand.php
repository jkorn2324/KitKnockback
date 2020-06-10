<?php

declare(strict_types=1);

namespace kitkb\commands\knockback;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use kitkb\KitKb;
use pocketmine\utils\TextFormat;


abstract class KnockbackCommand extends Command
{

    /** @var string */
    protected $type;

    public function __construct(string $type, string $description)
    {
        parent::__construct("kb-{$type}", $description, "Usage: kb-{$type} <name> <value> ", ["{$type}-kb"]);
        parent::setPermission("permission.kit.kb");
        $this->type = $type;
    }

    /**
     * @param CommandSender $sender - The Command sender.
     * @param array $args - The arguments for the command.
     * @return bool - Returns whether or not the user can execute the command.
     */
    protected function canExecute(CommandSender $sender, array $args)
    {
        if(!$this->testPermission($sender))
        {
            return false;
        }

        if(count($args) < 2)
        {
            $sender->sendMessage($this->getUsage());
            return false;
        }

        $name = $args[0]; $value = $args[1];
        $kitHandler = KitKb::getKitHandler();
        if(!$kitHandler->isKit($name))
        {
            $sender->sendMessage(TextFormat::RED . "Failed to update kit. Reason: Kit doesn't exist.");
            return false;
        }


        if(!is_numeric($value))
        {
            $type = $this->type === KitKb::KB_SPEED ? "whole number (EX: 5, 10)" : "decimal (0.4)";
            $sender->sendMessage(TextFormat::RED . "Failed to update the kit's kb. The input value must be a {$type}.");
            return false;
        }

        return true;
    }
}