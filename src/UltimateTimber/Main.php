<?php

declare(strict_types=1);

namespace UltimateTimber;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    public function onLoad(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        $block = $event->getBlock();
        $player = $event->getPlayer();

        if ($this->isLogBlock($block)) {
            $this->breakTree($block, $player);
        }
    }

    private function isLogBlock(Block $block): bool {
        return in_array($block->getId(), [
            VanillaBlocks::OAK_LOG()->getId(),
            VanillaBlocks::SPRUCE_LOG()->getId(),
            VanillaBlocks::BIRCH_LOG()->getId(),
            VanillaBlocks::JUNGLE_LOG()->getId(),
            VanillaBlocks::ACACIA_LOG()->getId(),
            VanillaBlocks::DARK_OAK_LOG()->getId()
        ]);
    }

    private function breakTree(Block $block, Player $player): void {
        $blocksToBreak = [$block];

        while (!empty($blocksToBreak)) {
            $currentBlock = array_pop($blocksToBreak);

            foreach ([[-1, 0, 0], [1, 0, 0], [0, -1, 0], [0, 1, 0], [0, 0, -1], [0, 0, 1]] as [$dx, $dy, $dz]) {
                $neighborBlock = $currentBlock->getPosition()->getWorld()->getBlock($currentBlock->getPosition()->add($dx, $dy, $dz));

                if ($this->isLogBlock($neighborBlock)) {
                    $blocksToBreak[] = $neighborBlock;
                }
            }

            $currentBlock->getPosition()->getWorld()->useBreakOn($currentBlock, $player);
        }
    }
}
