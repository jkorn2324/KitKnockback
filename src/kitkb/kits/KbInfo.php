<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:39
 */

declare(strict_types=1);

namespace kitkb\kits;

use kitkb\KitKb;
use pocketmine\utils\TextFormat;

class KbInfo
{
    /* @var float|int */
    private $xKb;

    /* @var float|int */
    private $yKb;

    /* @var int */
    private $speed;

    /**
     * KbInfo constructor.
     * @param float|int $xKb
     * @param float|int $yKb
     * @param int $speed
     */
    public function __construct($xKb = 0.4, $yKb = 0.4, $speed = 10)
    {
        $this->xKb = $xKb;
        $this->yKb = $yKb;
        $this->speed = $speed;
    }

    /**
     * @return int
     *
     * Gets the attack delay.
     */
    public function getSpeed() {
        return $this->speed;
    }

    /**
     * @return float|int
     *
     * Gets the y kb.
     */
    public function getYKb() {
        return $this->yKb;
    }

    /**
     * @return float|int
     *
     * Gets the x kb.
     */
    public function getXKb() {
        return $this->xKb;
    }

    /**
     * @param string $key
     * @param int|float $val
     */
    public function update(string $key, $val) {

        switch($key) {
            case KitKb::KB_X:
                $this->xKb = $val;
                break;
            case KitKb::KB_Y:
                $this->yKb = $val;
                break;
            case KitKb::KB_SPEED:
                $this->speed = $val;
                break;
        }
    }

    /**
     * @return array
     *
     * Exports the kit to an array.
     */
    public function toArray() {
        return [
            'xkb' => $this->xKb,
            'ykb' => $this->yKb,
            'speed' => $this->speed
        ];
    }

    /**
     * Displays the kit for the user.
     * @return string
     */
    public function display()
    {
        $displayArray = [
            TextFormat::BLUE . "Kit Information" . TextFormat::DARK_GRAY . ": " . TextFormat::WHITE . $this->name,
            TextFormat::GOLD . "KB-X" . TextFormat::DARK_GRAY . ": " . TextFormat::WHITE . $this->xKb,
            TextFormat::GOLD . "KB-Y" . TextFormat::DARK_GRAY . ": " . TextFormat::WHITE . $this->yKb,
            TextFormat::GOLD . "KB-Speed" . TextFormat::DARK_GRAY . ": " . TextFormat::WHITE . $this->speed
        ];

        return implode("\n", $displayArray);
    }
}