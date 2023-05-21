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
  
  public int $time;
  
  private Config $config;
  
  private array $envoys;
  
  public function onEnable(): void {
    
    self::$instance = $this;
    
    $this->saveResource('config.yml');
    $this->saveResource('locations.yml');
    $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    $this->time = $this->getEnvoysConfig()->get('time-to-spawn');
    for($i = 1; $i <=4; $i++) {
      $this->saveResource("Envoy_$i.png");
      $this->saveResource("Envoy_$i.json");
    }
    
    Utils::init($this);
    //DISABLE PLUGIN IF YOUR WORLD ISN'T CORRECT!!!
    if (is_null($this->getServer()->getWorldManager()->getWorldByName($this->config->get('world')))){
      $this->getServer()->getLogger()->warning("The WORLD specified in the BloodyEnvoys config.yml is NOT LOADED or DOES NOT EXIST! (Did you just install the plugin?)");
      $this->getServer()->getLogger()->error("BLOODYENVOYS WILL **NOT** WORK AS EXPECTED UNTIL THIS IS RESOLVED!");
    }
    
    $envoys = yaml_parse_file($this->getDataFolder() . 'locations.yml');
    foreach($envoys as $envoy){
      $x = $envoy['x'];
      $y = $envoy[1];
      $z = $envoy['z'];
      $yaw = $envoy['yaw'];
      $pitch = $envoy['pitch'];
      $this->envoys[] = new Location($x, $y, $z, $this->getServer()->getWorldManager()->getWorldByName($this->config->get('world')), $yaw, $pitch);
    }
    
    
    EntityFactory::getInstance()->register(AnimationEntity::class, function (World $world, CompoundTag $nbt): AnimationEntity {
      return new AnimationEntity(new Location(0, 0, 0, $world, 0, 0), 1);
    }, ["AnimationEntity"]);
    
    EntityFactory::getInstance()->register(Envoy::class, function (World $world, CompoundTag $nbt)  : Human {
      return new Envoy(EntityDataHelper::parseLocation($nbt, $world), Utils::getEnvoySkin(1));
    }, ["Envoy"]);
    
    $this->getScheduler()->scheduleRepeatingTask(new EnvoyTask($this), 5 * 60 * $this->time);
  }
  
  public static function getInstance(): Main {
    return self::$instance;
  }
  
  public function getEnvoysConfig(): Config {
    return $this->config;
  }
  
  public function getEnvoysLocations(): array {
    return $this->envoys;
  }
}
