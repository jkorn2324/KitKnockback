<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:23
 */

declare(strict_types=1);

namespace kitkb;

use kitkb\commands\CreateKitCommand;
use kitkb\commands\DeleteKitCommand;
use kitkb\commands\KitCommand;
use kitkb\kits\KitHandler;
use kitkb\Player\KitKbPlayer;
use pocketmine\entity\Effect;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class KitKb extends PluginBase
{

    private $dataFolder;

    private static $kitHandler;

    public function onEnable()
    {
        parent::onEnable();
        $this->getServer()->getPluginManager()->registerEvents(new KitKbListener(), $this);
        $this->initDataFolder();
        self::$kitHandler = new KitHandler($this);
        $this->registerCommands();
    }

    private function initDataFolder() {
        $this->dataFolder = $this->getDataFolder();
        if(!is_dir($this->dataFolder))
            mkdir($this->dataFolder);
    }

    public function onDisable()
    {
        parent::onDisable();
    }

    /**
     * @return KitHandler
     */
    public static function getKitHandler() {
        return self::$kitHandler;
    }

    private function registerCommands() {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register('createKit', new CreateKitCommand());
        $commandMap->register('giveKit', new KitCommand());
        $commandMap->register('deleteKit', new DeleteKitCommand());
    }

    /**
     * @param Player|KitKbPlayer $player
     * @return array
     */
    public static function inventoryToArray($player) {

        $inventory = $player->getInventory();

        $items = [];
        $armor = [];

        $size = $inventory->getSize();

        for($i = 0; $i < $size; $i++) {
            $item = $inventory->getItem($i);
            $items[] = $item;
        }

        for($i = 0; $i < 4; $i++) {
            $item = $inventory->getArmorItem($i);
            $armor[] = $item;
        }

        return ['items' => $items, 'armor' => $armor];
    }

    /**
     * @param Item $item
     * @return string
     */
    public static function itemToStr(Item $item) {

        $id = $item->getId();

        $meta = $item->getDamage();

        $count = $item->getCount();

        $enchants = $item->getEnchantments();

        $size = count($enchants);

        $enchantStr = '';

        if($size > 0) {
            $size--;
            $count = 0;
            foreach($enchants as $enchant) {
                $id = $enchant->getId();
                $level = $enchant->getLevel();
                $comma = $count === $size ? '' : ',';
                $str = "$id:$level";
                $enchantStr .= $str . $comma;
            }
        }

        return "$id:$count:$meta" . (($size > 0) ? "-$enchantStr" : '');
    }

    /**
     * @param string $string
     * @return Item|null
     */
    public static function strToItem(string $string) {

        $split = explode('-', $string);

        $itemPortion = strval($split[0]);

        $enchants = [];

        if(isset($split[1])) {
            $enchantPortion = strval($split[1]);
            $enchantsSplit = explode(',', $enchantPortion);
            foreach($enchantsSplit as $e) {
                $enchantData = explode(':', strval($e));
                if(isset($enchantData[0], $enchantData[1])) {
                    $id = intval($enchantData[0]);
                    $level = intval($enchantData[1]);
                    $enchant = Enchantment::getEnchantment($id)->setLevel($level);
                    $enchants[] = $enchant;
                }
            }
        }

        $itemData = explode(':', $itemPortion);

        $item = null;

        if(isset($itemData[0])) {
            $id = intval($itemData[0]);
            $count = 1;
            $meta = 0;
            if(isset($itemData[1])) {
                $count = intval($itemData[1]);
                if(isset($itemData[2]))
                    $meta = intval($itemData[2]);
            }

            $item = Item::get($id, $meta, $count);

            $size = count($enchants);

            if($size > 0) {
                foreach($enchants as $e)
                    $item->addEnchantment($e);
            }
        }

        return $item;
    }

    /**
     * @param Effect $effect
     * @return string
     */
    public static function effectToStr(Effect $effect) {
        $duration = $effect->getDuration();
        $id = $effect->getId();
        $amp = $effect->getAmplifier();
        return "$id:$amp:$duration";
    }

    /**
     * @param string $string
     * @return Effect|null
     */
    public static function strToEffect(string $string) {
        $effectData = explode(':', $string);
        $effect = null;
        if(isset($effectData[0])) {
            $id = intval($effectData[0]);
            $amplifier = 0;
            $duration = self::minutesToTicks(5);
            if(isset($effectData[1])) {
                $amplifier = intval($effectData[1]);
                if(isset($effectData[2]))
                    $duration = intval($effectData[2]);
            }
            $effect = Effect::getEffect($id)->setDuration($duration)->setAmplifier($amplifier);
        }
        return $effect;
    }

    /**
     * @param int $minutes
     * @return float|int
     */
    public static function minutesToTicks(int $minutes) {
        return $minutes * 1200;
    }

    /**
     * @param int $seconds
     * @return float|int
     */
    public static function secondsToTicks(int $seconds) {
        return $seconds * 20;
    }

    /**
     * @param int|string $index
     * @return int|string
     */
    public static function getArmorStr($index) {

        $arr = [0 => 'helmet', 1 => 'chestplate', 2 => 'leggings', 3 => 'boots'];

        if(is_string($index))
            $arr = ['helmet' => 0, 'chestplate' => 1, 'leggings' => 2, 'boots' => 3];

        $index = (is_int($index) ? $index % 4 : $index);
        return $arr[$index];
    }

    public static function getConsoleMsg() : string {
        return TextFormat::RED . 'Console cannot use this command.';
    }
}