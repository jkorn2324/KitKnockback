<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:25
 */

declare(strict_types=1);

namespace kitkb;


use kitkb\Player\KitKbPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;

class KitKbListener implements Listener
{

    /** @var KitKb */
    private $kitKb;

    public function __construct(KitKb $kb)
    {
        $this->kitKb = $kb;
        $kb->getServer()->getPluginManager()->registerEvents($this, $kb);
    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event) {
        $class = KitKbPlayer::class;
        $event->setPlayerClass($class);
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        if($player instanceof KitKbPlayer)
            $player->clearKit();
    }
}