<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:28
 */

declare(strict_types=1);

namespace kitkb\Player;


use kitkb\KitKb;
use kitkb\kits\Kit;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\Player;

class KitKbPlayer extends Player
{

    /* @var string|null */
    protected $currentKit = null;


    /**
     * @param Kit|string $kit
     *
     * Sets the current kit of the player.
     */
    public function setCurrentKit($kit) {
        $this->currentKit = ($kit instanceof Kit) ? $kit->getName() : $kit;
    }

    /**
     * @return bool
     *
     * Determines if the player has a kit.
     */
    public function hasKit() {
        return $this->currentKit !== null;
    }

    /**
     * Clears the kit from the player.
     */
    public function clearKit() {
        $this->currentKit = null;
    }

    /**
     * @param $damage
     * @param EntityDamageEvent $source
     * @return void
     *
     * Called when the player is attacked.
     */
    public function attack($damage, EntityDamageEvent $source)
    {
        parent::attack($damage, $source);

        if($source->isCancelled()) {
            return;
        }

        $kitHandler = KitKb::getKitHandler();
        if ($this->currentKit !== null and $kitHandler->isKit($this->currentKit)) {
            $kit = $kitHandler->getKit($this->currentKit);
            $kb = $kit->getKbInfo();
            $speed = $kb->getSpeed();
            if($source instanceof EntityDamageByEntityEvent) {
                $damager = $source->getDamager();
                if($damager instanceof KitKbPlayer and $damager->hasKit()) {
                    $this->attackTime = $speed;
                }
            }
        }
    }

    /**
     * @param Entity $attacker
     * @param $damage
     * @param $x
     * @param $z
     * @param float $base
     */
    public function knockBack(Entity $attacker, $damage, $x, $z, $base = 0.4)
    {
        $kitHandler = KitKb::getKitHandler();

        $xKb = $base;
        $yKb = $base;

        if($this->currentKit !== null and $kitHandler->isKit($this->currentKit)) {
            $kit = $kitHandler->getKit($this->currentKit);
            if($attacker instanceof KitKbPlayer and $attacker->hasKit()) {
                $kb = $kit->getKbInfo();
                $xKb = $kb->getXKb();
                $yKb = $kb->getYKb();
            }
        }

        $f = sqrt($x * $x + $z * $z);
        if($f <= 0){
            return;
        }

        $f = 1 / $f;

        $motion = new Vector3($this->motionX, $this->motionY, $this->motionZ);

        $motion->x /= 2;
        $motion->y /= 2;
        $motion->z /= 2;
        $motion->x += $x * $f * $xKb;
        $motion->y += $yKb;
        $motion->z += $z * $f * $xKb;

        if($motion->y > $yKb){
            $motion->y = $yKb;
        }

        $this->setMotion($motion);
    }

    /**
     * @return KitKbPlayer|Player|null
     */
    public function getPlayer()
    {
        return $this;
    }
}