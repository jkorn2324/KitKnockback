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
use kitkb\commands\ListKitCommand;
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

    const ARMOR_INDEXES = [
        'helmet',
        'chestplate',
        'leggings',
        'boots'
    ];

    /** @var string */
    private $dataFolder = "";

    /** @var KitHandler */
    private static $kitHandler;

    public function onEnable()
    {
        $this->initDataFolder();

        self::$kitHandler = new KitHandler($this);

        $this->registerCommands();
        new KitKbListener($this);
    }

    /**
     * Initializes the data folder.
     */
    private function initDataFolder() {
        $this->dataFolder = $this->getDataFolder();
        if(!is_dir($this->dataFolder)) {
            mkdir($this->dataFolder);
        }
    }

    /**
     * @return KitHandler
     *
     * Gets the kit handler.
     */
    public static function getKitHandler() {
        return self::$kitHandler;
    }

    /**
     * Registers the commands to the server.
     *
     * @return void
     */
    private function registerCommands()
    {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register('createKit', new CreateKitCommand());
        $commandMap->register('giveKit', new KitCommand());
        $commandMap->register('deleteKit', new DeleteKitCommand());
        $commandMap->register('listkits', new ListKitCommand());
    }

    /**
     * @param Player|KitKbPlayer $player
     * @return array
     *
     * Helper function used to convert an inventory to an array.
     */
    public static function inventoryToArray($player) {

        $inventory = $player->getInventory();

        return [
            'items' => $inventory->getContents(),
            'armor' => $inventory->getArmorContents()
        ];
    }

    /**
     * @param Item $item
     * @return string
     *
     * Converts an item to a string.
     */
    public static function itemToStr(Item $item) {

        $enchants = $item->getEnchantments();

        $size = count($enchants);

        $enchantStr = '';

        if($size > 0) {
            $size--;
            $count = 0;
            foreach($enchants as $enchant) {
                $comma = $count === $size ? '' : ',';
                $str = "{$enchant->getId()}:{$enchant->getLevel()}";
                $enchantStr .= $str . $comma;
            }
        }

        return "{$item->getId()}:{$item->getCount()}:{$item->getDamage()}" . (($size > 0) ? "-$enchantStr" : '');
    }

    /**
     * @param string $string
     * @return Item|null
     *
     * Converts a string to an item.
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
                    $enchantID = intval($enchantData[0]);
                    $level = intval($enchantData[1]);
                    $enchant = Enchantment::getEnchantment($enchantID)->setLevel($level);
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
                if(isset($itemData[2])) {
                    $meta = intval($itemData[2]);
                }
            }

            $item = Item::get($id, $meta, $count);

            if(count($enchants) > 0) {
                foreach($enchants as $e) {
                    $item->addEnchantment($e);
                }
            }
        }

        return $item;
    }

    /**
     * @param Effect $effect
     * @return string
     */
    public static function effectToStr(Effect $effect) {
        return "{$effect->getId()}:{$effect->getAmplifier()}:{$effect->getDuration()}";
    }

    /**
     * @param string $string
     * @return Effect|null
     *
     * Converts a string to an effect.
     */
    public static function strToEffect(string $string) {
        $effectData = explode(':', $string);
        if(isset($effectData[0])) {
            $id = intval($effectData[0]);
            $amplifier = 0;
            $duration = self::minutesToTicks(5);
            if(isset($effectData[1])) {
                $amplifier = intval($effectData[1]);
                if(isset($effectData[2])) {
                    $duration = intval($effectData[2]);
                }
            }
            $effect = Effect::getEffect($id);
            if($effect !== null) {
                return $effect->setDuration($duration)->setAmplifier($amplifier);
            }
        }
        return null;
    }

    /**
     * @param int $minutes
     * @return float|int
     *
     * Helper function used to convert minutes to ticks.
     */
    public static function minutesToTicks(int $minutes) {
        return $minutes * 1200;
    }

    /**
     * @param int $seconds
     * @return float|int
     *
     * Helper function used to convert seconds to ticks.
     */
    public static function secondsToTicks(int $seconds) {
        return $seconds * 20;
    }

    /**
     * @param int|string $index
     * @return int|string
     *
     * Gets the armor string.
     */
    public static function getArmorStr($index) {

        $arr = self::ARMOR_INDEXES;

        if(is_string($index)) {
            $arr = array_flip($arr);
        }

        $index = (is_int($index) ? $index % 4 : $index);
        return $arr[$index];
    }

    /**
     * @return string
     *
     * Gets the console message.
     */
    public static function getConsoleMsg() : string {
        return TextFormat::RED . 'Console cannot use this command.';
    }
}