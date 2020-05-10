<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 20:59
 */

declare(strict_types=1);

namespace kitkb\commands;


use kitkb\KitKb;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class CreateKitCommand extends Command
{

    public function __construct()
    {
        parent::__construct('create-kit', 'Creates a kit with all items from inventory.', 'Usage: /create-kit <name>', ['kit-create']);
        parent::setPermission('permission.kit.create');
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
        $msg = null;

        if($sender instanceof Player) {
            if($this->testPermission($sender)) {
                if(isset($args[0])) {
                    $name = strval($args[0]);
                    $kitHandler = KitKb::getKitHandler();
                    if(!$kitHandler->isKit($name)) {
                        $kitHandler->createKit($name, $sender->getPlayer());
                        $msg = TextFormat::GREEN . 'Successfully created a new kit.';
                    } else {
                        $msg = TextFormat::RED . 'Failed to create a new kit. Reason: Kit already exists.';
                    }
                } else {
                    $msg = $this->getUsage();
                }
            }
        } else {
            $msg = KitKb::getConsoleMsg();
        }

        if($msg !== null) $sender->sendMessage($msg);

        return true;
    }
}