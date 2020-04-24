<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:36
 */

declare(strict_types=1);

namespace kitkb\kits;

use kitkb\KitKb;
use kitkb\Player\KitKbPlayer;
use pocketmine\entity\Effect;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;

class Kit
{
    /* @var string */
    private $name;

    /* @var array|Item[] */
    private $items;

    /* @var array|Item[] */
    private $armor;

    /* @var KbInfo */
    private $kbInfo;

    /* @var array|Effect[] */
    private $effects;

    /**
     * Kit constructor.
     * @param string $name
     * @param array $items
     * @param array $armor
     * @param array $effects
     * @param KbInfo|null $info
     */
    public function __construct(string $name, array $items = [], array $armor = [], array $effects = [], KbInfo $info = null)
    {
        $this->name = $name;
        $this->items = $items;
        $this->armor = $armor;
        $this->effects = $effects;
        $this->kbInfo = ($info !== null) ? $info : new KbInfo();
    }

    /**
     * @return KbInfo
     */
    public function getKbInfo() {
        return $this->kbInfo;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param KitKbPlayer $player
     * @param bool $message
     */
    public function giveTo(KitKbPlayer $player, bool $message = true) {

        $player->setCurrentKit($this);

        $inventory = $player->getInventory();

        $itemSize = count($this->items);
        $armorSize = count($this->armor);

        for($i = 0; $i < $itemSize; $i++) {
            $item = $this->items[$i];
            $inventory->setItem($i, $item);
        }

        for($i = 0; $i < $armorSize; $i++) {
            $item = $this->armor[$i];
            $inventory->setArmorItem($i, $item);
        }

        foreach($this->effects as $effect) {
            $player->addEffect($effect);
        }

        if($message) {
            $player->sendMessage(TextFormat::GREEN . '[Kit-KB] Successfully Received ' . $this->name . '!');
        }
    }

    /**
     * @return array
     *
     * Exports the kit to an array.
     */
    public function toArray() {

        $items = [];
        foreach($this->items as $item) {
            $str = KitKb::itemToStr($item);
            $items[] = $str;
        }

        $armor = [];
        $size = count($this->armor);
        for($i = 0; $i < $size; $i++) {
            $key = KitKb::getArmorStr($i);
            $item = $this->armor[$i];
            $value = KitKb::itemToStr($item);
            $armor[$key] = $value;
        }

        $effects = [];
        foreach($this->effects as $effect) {
            $value = KitKb::effectToStr($effect);
            $effects[] = $value;
        }

        $kb = $this->kbInfo->toArray();

        return ['items' => $items, 'armor' => $armor, 'effects' => $effects, 'kb' => $kb];
    }

}