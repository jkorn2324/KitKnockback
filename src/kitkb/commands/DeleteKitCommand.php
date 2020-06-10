<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 22:01
 */

declare(strict_types=1);

namespace kitkb\commands;


use kitkb\KitKb;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DeleteKitCommand extends Command
{
    public function __construct()
    {
        parent::__construct('delete-kit', 'Deletes a kit from the list.', 'Usage: /delete-kit <name>', ['kit-delete']);
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
                    if($kitHandler->isKit($name)) {
                        $kitHandler->deleteKit($name);
                        $msg = TextFormat::GREEN . 'Successfully deleted a kit.';
                    } else {
                        $msg = TextFormat::RED . 'Failed to delete kit. Reason: Kit does not exist.';
                    }
                } else {
                    $msg = $this->getUsage();
                }
            }
        } else {
            $msg = KitKb::getConsoleMsg();
        }

        if($msg !== null) {
            $sender->sendMessage($msg);
        }

        return true;
    }
}