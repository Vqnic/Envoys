<?php

namespace Vanic\Bloody\Envoys\Entity;


use pocketmine\entity\Skin;
use pocketmine\entity\Human;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use Vanic\Bloody\Envoys\Main;
use Vanic\Bloody\Envoys\Utils;
use pocketmine\entity\Location;
use pocketmine\world\ChunkLoader;
use pocketmine\world\format\Chunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\world\particle\HugeExplodeSeedParticle;

class Envoy extends Human implements ChunkLoader {
  
  private int $tier;
  
  private int $progress;
  
  public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null, ?int $tier = 1) {
    $location->getWorld()->registerChunkLoader($this, $location->getFloorX() >> Chunk::COORD_BIT_SIZE, $location->getFloorZ() >> Chunk::COORD_BIT_SIZE, true);
    $this->setCanSaveWithChunk(false);
    parent::__construct($location, $skin, $nbt);
    
    
    $this->tier = $tier;
    $this->setScale($tier * 0.3 + 1);
    $this->progress = 10 * $tier;
  
    $this->setImmobile();
    $this->setNameTagAlwaysVisible();
    $this->setNameTag("§f§lTIER $tier ENVOY\n§7Smash to open!");
    $this->setScoreTag("§l§8(§7PROGRESS§8)§r\n" . Utils::createProgressBar($this->getProgress(), $this->getProgress()));
  }
  
  public function attack(EntityDamageEvent $source): void {
    $source->cancel();
    if ($source instanceof EntityDamageByEntityEvent && $source->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
      $damager = $source->getDamager();
      if ($damager instanceof Player && $damager->isSurvival()) {
        if ($this->getProgress() <= 0) {
          for ($i = 0; $i < 20; $i++) {
            $this->getWorld()->addParticle($this->getLocation(), new HugeExplodeSeedParticle());
            $gems = new AnimationEntity($this->getLocation(), $this->tier);
            $gems->setMotion(new Vector3(lcg_value() * 0.5 - 0.1, 1, lcg_value() * 0.5 - 0.1));
            $gems->spawnToAll();
          }
          $this->flagForDespawn();
          $config = Main::getInstance()->getEnvoysConfig();
          $coins = mt_rand($config->get("min-coins-t$this->tier"), $config->get("max-coins-t$this->tier"));
          Utils::addMoney($damager, $coins);
        }
        $this->setProgress($this->getProgress() - 1);
        $this->setScoreTag("§l§8(§7PROGRESS§8)§r\n" . Utils::createProgressBar($this->getProgress(), 10 * $this->tier));
        $this->updateMovement();
        for ($i = 0; $i < 5; $i++) {
          $gems = new AnimationEntity($this->getLocation(), $this->tier);
          $gems->setMotion(new Vector3(lcg_value() * (mt_rand(0, 1) ? -0.5 : 0.5), 0.5, lcg_value() * (mt_rand(0, 1) ? -0.5 : 0.5)));
          $gems->spawnToAll();
        }
      }
      parent::attack($source);
    }
  }
  
  public function setMotion(Vector3 $motion): bool {
    return false;
  }
  
  public function getProgress() : int {
    return $this->progress;
  }
  
  public function setProgress($progress) : void {
    $this->progress = $progress;
  }
  
}