<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:56
 */

declare(strict_types=1);

namespace kitkb\kits;

use kitkb\KitKb;
use kitkb\Player\KitKbPlayer;
use pocketmine\Player;
use pocketmine\utils\Config;

class KitHandler
{

    /* @var string */
    private $path;

    /* @var array|Kit[] */
    private $kits;

    /* @var Config */
    private $config;

    /**
     * KitHandler constructor.
     * @param KitKb $kb
     */
    public function __construct(KitKb $kb)
    {
        $this->path = $kb->getDataFolder() . '/kits.yml';
        $this->kits = [];
        $this->initConfig();
    }

    private function initConfig() {

        $this->config = new Config($this->path, Config::YAML, []);

        if(!file_exists($this->path))
            $this->config->save();
        else {
            $keys = $this->config->getAll(true);
            foreach($keys as $key) {
                $key = strval($key);
                $kit = $this->parseKit($key);
                if($kit !== null)
                    $this->kits[$key] = $kit;
            }
        }
    }

    /**
     * @param string $name
     * @return Kit|null
     */
    private function parseKit(string $name) {

        $kit = null;

        if($this->config->exists($name)) {

            $value = $this->config->get($name);

            if(isset($value['items'], $value['armor'], $value['effects'], $value['kb'])) {

                $items = [];
                $armor = [];
                $effects = [];

                $itemData = $value['items'];
                $armorData = $value['armor'];
                $effectsData = $value['effects'];
                $kbData = $value['kb'];

                foreach($itemData as $iData) {
                    $item = KitKb::strToItem(strval($iData));
                    $items[] = $item;
                }

                $keys = array_keys($armorData);

                foreach($keys as $key) {
                    $key = strval($key);
                    $value = $armorData[$key];
                    $index = KitKb::getArmorStr($key);
                    $armor[$index] = KitKb::strToItem($value);
                }

                foreach($effectsData as $eData) {
                    $effect = KitKb::strToEffect($eData);
                    $effects[] = $effect;
                }

                $kb = null;
                if(isset($kbData['xkb'], $kbData['ykb'], $kbData['speed'])) {
                    $speed = intval($kbData['speed']);
                    $yKb = floatval($kbData['ykb']);
                    $xKb = floatval($kbData['xkb']);
                    $kb = new KbInfo($xKb, $yKb, $speed);
                }

                $kit = new Kit($name, $items, $armor, $effects, $kb);
            }
        }
        return $kit;
    }


    /**
     * @param string $name
     * @param Player|KitKbPlayer $player
     */
    public function createKit(string $name, $player) {

        $invArr = KitKb::inventoryToArray($player);

        $items = $invArr['items'];
        $armor = $invArr['armor'];

        $effects = $player->getEffects();

        $kit = new Kit($name, $items, $armor, $effects);

        if(!$this->config->exists($name)) {
            $map = $kit->toArray();
            $this->config->set($name, $map);
            $this->config->save();
        }

        $this->kits[$name] = $kit;
    }

    /**
     * @param string $name
     */
    public function deleteKit(string $name) {

        if(isset($this->kits[$name]))
            unset($this->kits[$name]);

        if($this->config->exists($name)) {
            $this->config->remove($name);
            $this->config->save();
        }
    }

    /**
     * @param string $kit
     * @return Kit|null
     */
    public function getKit(string $kit) {
        $result = null;
        if(isset($this->kits[$kit]))
            $result = $this->kits[$kit];
        return $result;
    }

    /**
     * @param string $kit
     * @return bool
     */
    public function isKit(string $kit) {
        return isset($this->kits[$kit]);
    }

}