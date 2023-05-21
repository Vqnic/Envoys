<?php

namespace Vanic\Bloody\Envoys\Entity;

use pocketmine\player\Player;
use pocketmine\entity\Location;
use pocketmine\item\VanillaItems;
use pocketmine\entity\object\ItemEntity;

class AnimationEntity extends ItemEntity {
  
  public function __construct(Location $location, int $tier) {
    $drops = [
      VanillaItems::IRON_INGOT(),
      VanillaItems::GOLD_INGOT(),
      VanillaItems::DIAMOND(),
      VanillaItems::EMERALD()
    ];
    parent::__construct($location, $drops[$tier - 1]);
  }
  
  public function onHitGround(): ?float {
    $this->flagForDespawn();
    return null;
  }
  
  public function onCollideWithPlayer(Player $player): void {
    $this->flagForDespawn();
  }
}