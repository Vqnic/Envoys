<?php

namespace Vanic\Bloody\Envoys\Entity;

use pocketmine\player\Player;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\item\StringToItemParser;
use pocketmine\entity\object\ItemEntity;

class AnimationEntity extends ItemEntity {
  
	public function __construct(Location $location, string $stringitem) {
		$drops = [
			VanillaItems::IRON_INGOT(),
			VanillaItems::GOLD_INGOT(),
			VanillaItems::DIAMOND(),
			VanillaItems::EMERALD()
		];
	
		$item = StringToItemParser::getInstance()->parse($stringitem) ?? $drops[array_rand($drops)];
		parent::__construct($location, $item);
	}
  
	public function onHitGround(): ?float {
		$this->flagForDespawn();
		return null;
	}
  
	public function onCollideWithPlayer(Player $player): void {
		$this->flagForDespawn();
	}
}