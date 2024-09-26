<?php

namespace AntiCheat\checks;

use pocketmine\player\Player;

class FlyCheck {

    public function detect(Player $player): bool {
        return !$player->isOnGround() && $player->getMotion()->y == 0;
    }
}
