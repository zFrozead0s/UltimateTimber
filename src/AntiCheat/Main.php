<?php

namespace AntiCheat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use AntiCheat\checks\{KillAuraCheck, TimerCheck, NoClipCheck, AutoSprintCheck, ReachCheck};
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Embed;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    private $webhookUrl = "https://discord.com/api/webhooks/your-webhook-id/your-webhook-token";

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public static function sendAlert(string $message): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->hasPermission("alerts.ac")) {
                $player->sendMessage($message);
            }
        }

        self::sendToWebhook($message);
    }

    public static function sendToWebhook(string $message): void {
        $webhook = new Webhook(self::$webhookUrl);
        $msg = new Message();
        $embed = new Embed();
        $embed->setTitle("AntiCheat Alert")
              ->setDescription($message)
              ->setColor(0xFF0000);
        $msg->addEmbed($embed);
        $webhook->send($msg);
    }

    public function onPlayerMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        TimerCheck::check($player, $event);
        NoClipCheck::check($player, $event);
        AutoSprintCheck::check($player, $event);
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        if ($damager instanceof Player) {
            KillAuraCheck::check($damager, $event);
            ReachCheck::check($damager, $event);
        }
    }
}
