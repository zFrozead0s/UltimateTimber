<?php

namespace AntiCheat\checks;

use pocketmine\player\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use AntiCheat\Main;

class KillAuraCheck {

    private static $violationCount = [];

    public static function check(Player $player, EntityDamageByEntityEvent $event): void {
        $playerName = $player->getName();

        if (!isset(self::$violationCount[$playerName])) {
            self::$violationCount[$playerName] = 0;
        }

        if (true) {
            self::$violationCount[$playerName]++;
            $count = self::$violationCount[$playerName];

            if ($count >= 3) {
                $message = "§c[Anti-Cheat] §7($playerName) está usando KillAura x$count";
                Main::sendAlert($message);
            }
        }
    }
}
