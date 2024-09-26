<?php

namespace AntiCheat\checks;

use pocketmine\player\Player;

class AutoClickCheck {

    private int $maxClicksPerSecond = 13;

    public function detect(Player $player, array &$clickTimes): bool {
        $currentTime = microtime(true);

        if (!isset($clickTimes[$player->getName()])) {
            $clickTimes[$player->getName()] = [];
        }

        $clickTimes[$player->getName()][] = $currentTime;

        $clickTimes[$player->getName()] = array_filter($clickTimes[$player->getName()], function($time) use ($currentTime) {
            return ($currentTime - $time) <= 1;
        });

        return count($clickTimes[$player->getName()]) > $this->maxClicksPerSecond;
    }
}
