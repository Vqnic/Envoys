<?php

namespace Vanic\Bloody\Envoys;

use pocketmine\world\World;
use pocketmine\entity\Human;
use pocketmine\utils\Config;
use pocketmine\entity\Location;
use pocketmine\plugin\PluginBase;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\EntityFactory;
use Vanic\Bloody\Envoys\Entity\Envoy;
use pocketmine\entity\EntityDataHelper;
use Vanic\Bloody\Envoys\Entity\AnimationEntity;

class Main extends PluginBase {
  
	private static Main $instance;
	public int $cooldown;
	private Config $config;
	private array $envoys = [];
  
	public function onEnable(): void {
		self::$instance = $this;
    
		foreach($this->getResources() as $path => $splFile) {
			$this->saveResource($path);
		}
		
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->cooldown = $this->config->getNested("setting.spawn.cooldown");

		$worldmanager = $this->getServer()->getWorldManager();
		foreach($this->config->get('worlds') as $world_folder => $envoys) {
			if(is_dir($this->getServer()->getDataPath() . "worlds/{$world_folder}")) {
				if(!$worldmanager->isWorldLoaded($world_folder)) {
					if ($this->config->getNested("setting.world.forceload")) {
						$worldmanager->loadWorld($world_folder);
					} else {
						$this->getServer()->getLogger()->info("{$world_folder} is found but setting.world.forceload is disabled.. cannot register envoys");
					}
				}

				if (($world = $this->getServer()->getWorldManager()->getWorldByName($world_folder)) instanceof World) {
					foreach($envoys as $id => $data) {
						$p = $data["location"];
						$data["location"] = new Location($p[0], $p[1], $p[2], $world, $p[3], $p[4]);
						$this->envoys[$world_folder][$id] = $data;
					}
				}
			}
		}
		
		if (count($this->envoys) < 1) {
			$this->getServer()->getLogger()->error("There were no valid envoys found.. plugin will not proceed");
			return;
		}

		Utils::init($this);
    /*
		EntityFactory::getInstance()->register(AnimationEntity::class, function (World $world, CompoundTag $nbt): AnimationEntity {
		  return new AnimationEntity(new Location(0, 0, 0, $world, 0, 0), 1);
		}, ["AnimationEntity"]);

		EntityFactory::getInstance()->register(Envoy::class, function (World $world, CompoundTag $nbt)  : Human {
		  return new Envoy(EntityDataHelper::parseLocation($nbt, $world), Utils::getEnvoySkin(1));
		}, ["Envoy"]);
    */
		$this->getScheduler()->scheduleRepeatingTask(new EnvoyTask($this), 20 * 60 * $this->cooldown);
	}
  
	public static function getInstance(): Main {
		return self::$instance;
	}
  
	public function getEnvoysConfig(): Config {
		return $this->config;
	}
  
	public function getRegisteredEnvoys(): array {
		return $this->envoys;
	}
}
