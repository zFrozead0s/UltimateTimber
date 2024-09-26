<?php

namespace AntiCheat\checks;

use pocketmine\player\Player;

class SpeedCheck {

    private float $maxSpeed = 0.7;

    public function detect(Player $player): bool {
        return $player->getMotion()->lengthSquared() > $this->maxSpeed;
    }
}
