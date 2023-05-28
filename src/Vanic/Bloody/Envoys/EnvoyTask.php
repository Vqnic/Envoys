<?php

namespace Vanic\Bloody\Envoys;

use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use Vanic\Bloody\Envoys\Entity\Envoy;

class EnvoyTask extends Task {

	private $config, $world = "soon";
	public function __construct(private Main $plugin) {
		$this->config = $plugin->getEnvoysConfig();
	}

	public function onRun(): void {
		$plugin = $this->plugin;
		$config = $this->config;
		$server = $plugin->getServer();
		
		$online = count($server->getOnlinePlayers());
		if ($online === 0)
			return;
		
		switch ($plugin->cooldown) {
			case 30: case 20: case 10:
			case 5: case 3: case 1:
				$server->broadcastMessage($this->translate($config->getNested("message.chat.next_spawn")));
			break;
			
			default:
				$plugin->cooldown = $config->getNested("setting.spawn.cooldown");
				if ($online >= $config->getNested("setting.spawn.online")) {
					$registered = $plugin->getRegisteredEnvoys();

					$worlds =  array_keys($registered);
					$folder = $worlds[array_rand($worlds)];

					$world = $server->getWorldManager()->getWorldByName($folder);
					foreach ($world->getEntities() as $entity) {
						if ($entity instanceof Envoy)
							$entity->flagForDespawn();
					}

					$envoys = $registered[$folder];
					foreach($envoys as $i => $data) {
						$envoy = new Envoy($data["location"], Utils::getEnvoySkin($data["texture"]), null, $data);
						$envoy->spawnToAll();
					}

					$this->world = $folder;
					$server->broadcastMessage($this->translate($config->getNested("message.chat.spawned")));
					$server->broadcastMessage($this->translate($config->getNested("message.chat.next_spawn")));
				} else {
					$server->broadcastMessage($this->translate($config->getNested("message.chat.not_enough_players")));
				}
			break;
		}
		$plugin->cooldown -= 1;
	}
	
	private function translate(string $message) : string {
		$config = $this->config;
		
		$message = ("{prefix} " . $message);
		$message = str_replace("{world}", $this->world, $message);
		$message = str_replace("{time}", $this->plugin->cooldown, $message);
		$message = str_replace("{online}", $config->getNested("setting.spawn.online"), $message);
		$message = str_replace("{cooldown}", $config->getNested("setting.spawn.cooldown"), $message);
		$message = str_replace("{prefix}", $config->getNested("message.prefix"), $message);
		
		
		return $message;
	}
}
