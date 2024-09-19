<?php

namespace AntiCheat\checks;

use pocketmine\player\Player;
use pocketmine\event\player\PlayerMoveEvent;
use AntiCheat\Main;

class NoClipCheck {

    private static $violationCount = [];

    public static function check(Player $player, PlayerMoveEvent $event): void {
        $playerName = $player->getName();

        if (!isset(self::$violationCount[$playerName])) {
            self::$violationCount[$playerName] = 0;
        }

        if ($player->isInsideSolid()) { 
            self::$violationCount[$playerName]++;
            $count = self::$violationCount[$playerName];

            if ($count >= 3) {
                $message = "§c[Anti-Cheat] §7($playerName) está usando NoClip x$count";
                Main::sendAlert($message);
            }
        }
    }
}
