<?php

namespace AntiCheat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use AntiCheat\checks\FlyCheck;
use AntiCheat\checks\SpeedCheck;
use AntiCheat\checks\AutoClickCheck;

class ACLoader extends PluginBase implements Listener {

    private array $clickTimes = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();

        if ($player->hasPermission("ac.bypass")) return;

        $flyCheck = new FlyCheck();
        $speedCheck = new SpeedCheck();

        if ($flyCheck->detect($player)) {
            $this->sendAlert($player, "is probably using FLy");
        }

        if ($speedCheck->detect($player)) {
            $this->sendAlert($player, "is probably using Speed");
        }
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $player = $event->getPlayer();

        if ($player->hasPermission("ac.bypass")) return;

        $autoClickCheck = new AutoClickCheck();
        if ($autoClickCheck->detect($player, $this->clickTimes)) {
            $this->sendAlert($player, "is probably using AutoClick");
        }
    }

    private function sendAlert($player, string $message): void {
        foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
            if ($onlinePlayer->hasPermission("ac.alert")) {
                $onlinePlayer->sendMessage("[AntiCheat] " . $player->getName() . ": " . $message);
            }
        }

        $this->sendWebhook($player, $message);
    }

    private function sendWebhook($player, string $message): void {
        $webhookUrl = "your webhook url";

        $playerName = $player->getName();
        $playerPing = $player->getNetworkSession()->getPing();

        $data = [
            "username" => "AntiCheat",
            "embeds" => [
                [
                    "title" => "AC Level 1",
                    "description" => "$playerName is probably using $message",
                    "color" => 16711680, // Rojo
                    "fields" => [
                        [
                            "name" => "Ping",
                            "value" => (string)$playerPing,
                            "inline" => true
                        ],
                        [
                            "name" => "Server",
                            "value" => ">>> IP: RavinePvP.ddns.net\nPort: 4600",
                            "inline" => false
                        ]
                    ],
                    "footer" => [
                        "text" => "AntiCheat System"
                    ]
                ]
            ]
        ];

        $opts = [
            "http" => [
                "method" => "POST",
                "header" => "Content-type: application/json",
                "content" => json_encode($data)
            ]
        ];

        $context = stream_context_create($opts);
        file_get_contents($webhookUrl, false, $context);
    }
}
