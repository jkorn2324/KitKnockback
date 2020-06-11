<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 21:33
 */

declare(strict_types=1);

namespace kitkb\commands;


use kitkb\KitKb;
use kitkb\Player\KitKbPlayer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class KitCommand extends Command
{

    public function __construct()
    {
        parent::__construct('kit', 'Gives the kit to the user of the command.', 'Usage: /kit <name>', ['give-kit', 'kit-give']);
        parent::setPermission("permission.kit.give");
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

            if(!$this->testPermission($sender))
            {
                return true;
            }

            if(isset($args[0])) {
                $name = strval($args[0]);
                $kitHandler = KitKb::getKitHandler();
                if($kitHandler->isKit($name)) {
                    $kit = $kitHandler->getKit($name);
                    $p = $sender->getPlayer();
                    if(!$p instanceof KitKbPlayer) {
                        $msg = TextFormat::RED . "Received kit, but custom knocback isn't enabled due to PocketMine bug.";
                        $kit->giveTo($p);
                    } else {
                        if(!$p->hasKit()) {
                            $kit->giveTo($p);
                        } else {
                            $msg = TextFormat::RED . 'Failed to receive kit. Reason: Already have kit (Use /kill to fix).';
                        }
                    }

                } else {
                    $msg = TextFormat::RED . 'Failed to receive kit. Reason: Kit does not exist.';
                }
            } else {
                $msg = $this->getUsage();
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