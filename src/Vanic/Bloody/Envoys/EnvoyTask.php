<?php

namespace Vanic\Bloody\Envoys;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use Vanic\Bloody\Envoys\Entity\Envoy;

class EnvoyTask extends Task {
  
  
  private Main $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function onRun(): void {
    $plugin = $this->plugin;
    $server = $plugin->getServer();
    $config = $plugin->getEnvoysConfig();
    $prefix = $config->get('prefix');
    if($plugin->time == 0) {
      if (is_null($server->getWorldManager()->getWorldByName($config->get('world')))){
        $server->getLogger()->error("The world specified in the Envoys config.yml does not exist, so envoys were not spawned! :(");
        return;
      }
      $playercount = 0;
      $world = $server->getWorldManager()->getWorldByName($config->get('world'));
      foreach ($world->getEntities() as $entity) {
        if ($entity instanceof Envoy)
          $entity->flagForDespawn();
        if ($entity instanceof Player)
          $playercount++;
      }
      if ($playercount >= $config->get('online-to-spawn')) {
        $plugin->time = $config->get('time-to-spawn');
        $server->broadcastMessage($prefix . $config->get('envoys-spawned'));
        $server->broadcastMessage($prefix . str_replace("{time}", $plugin->time, $config->get('envoys-timer')));
        for ($i = 0; $i < 4; $i++) {
          $tier = mt_rand(1, 4);
          $envoy = new Envoy($plugin->getEnvoysLocations()[$i], Utils::getEnvoySkin($tier), null, $tier);
          $envoy->spawnToAll();
        }
      }else{
        $server->broadcastMessage($prefix .  $config->get('envoys-not-enough-players'));
      }
    } else {
      switch ($plugin->time) {
        case 30;
        case 20;
        case 10;
        case 5;
        case 1;
          $server->broadcastMessage($prefix . str_replace( "{time}", $plugin->time, $config->get('envoys-timer')));
          break;
      }
    }
    $plugin->time -= 1;
  }
}
