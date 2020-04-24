<?php
/**
 * Created by PhpStorm.
 * User: jkorn2324
 * Date: 2019-07-01
 * Time: 18:39
 */

declare(strict_types=1);

namespace kitkb\kits;


class KbInfo
{

    public static $XKB = 'x';
    public static $YKB = 'y';
    public static $SPEED = 'speed';

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
     */
    public function getSpeed() {
        return $this->speed;
    }

    /**
     * @return float|int
     */
    public function getYKb() {
        return $this->yKb;
    }

    /**
     * @return float|int
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
            case self::$XKB:
                $this->xKb = $val;
                break;
            case self::$YKB:
                $this->yKb = $val;
                break;
            case self::$SPEED:
                $this->speed = $val;
                break;
        }
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'xkb' => $this->xKb,
            'ykb' => $this->yKb,
            'speed' => $this->speed
        ];
    }
}