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
	private array $coins = [];
	private string $itemation;
  
	public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null, array $data) {
		$location->getWorld()->registerChunkLoader($this, $location->getFloorX() >> Chunk::COORD_BIT_SIZE, $location->getFloorZ() >> Chunk::COORD_BIT_SIZE, true);
		$this->setCanSaveWithChunk(false);
		parent::__construct($location, $skin, $nbt);
		$this->tier = $data["tier"];
		$this->coins = $data["coinlimit"];
		$this->setScale($this->tier * 0.3 + 1);
		$this->progress = 10 * $this->tier;

		$this->setImmobile();
		$this->setNameTagAlwaysVisible();
		$this->setNameTag("§f§l". $data["displayname"] ."\n§7Smash to open!");
		$this->setScoreTag("§l§8(§7PROGRESS§8)§r\n" . Utils::createProgressBar($this->getProgress(), $this->getProgress()));
		
		//let's just keep it :>
		$this->itemation = $data["itemation"];
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
					$coins = mt_rand($this->coins[0], $this->coins[1]);
					Utils::addMoney($damager, $coins);
				}

				$this->progress -= 1;
				$this->setScoreTag("§l§8(§7PROGRESS§8)§r\n" . Utils::createProgressBar($this->progress, 10 * $this->tier));
				$this->updateMovement();
				
				$gem = new AnimationEntity($this->getLocation(), $this->itemation);
				for ($i = 0; $i < 5; $i++) {
					$gem->setMotion(new Vector3(lcg_value() * (mt_rand(0, 1) ? -0.5 : 0.5), 0.5, lcg_value() * (mt_rand(0, 1) ? -0.5 : 0.5)));
					$gem->spawnToAll();
				}
				
				if ($this->tier >= 2) {
					//fireworks here, soon
				}

				if ($this->tier >= 3) {
					//lightning here, soon
				}
				
				//yes the purpose is that the higher tier will get all the sfx
			}
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