<?php

namespace Vanic\Bloody\Envoys;

use pocketmine\entity\Skin;
use pocketmine\player\Player;
use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\libs\cooldogedev\libSQL\context\ClosureContext;

class Utils {
  
	private static Main $main;
	private static mixed $eco;
	private static bool $validEconomy = false;
  
	public static function init(Main $plugin) {
		self::$main = $plugin;
		$server = self::$main->getServer();
		self::$eco = self::$main->getEnvoysConfig()->getNested("setting.economy");
		if (is_null($server->getPluginManager()->getPlugin(self::$eco))) {
			$server->getLogger()->warning("The ECONOMY PLUGIN specified in the BloodyEnvoys config.yml is NOT INSTALLED or has been INCORRECTLY TYPED! (Did you just install the plugin?)");
			$server->getLogger()->warning("Valid values are 'BedrockEconomy', 'Capital', or 'EconomyAPI' ... you have provided '" . self::$eco . "', but that plugin does not exist on this server!");
			$server->getLogger()->error("BLOODYENVOYS WILL **NOT** WORK AS EXPECTED UNTIL THIS IS RESOLVED!");
		} else {
		  self::$validEconomy = true;
		}
	}
  
	public static function getEnvoySkin(string $id) : Skin {
		$path = self::$main->getDataFolder() . "texture/{$id}/texture.png";
		$img = @imagecreatefrompng($path);
		$skinbytes = "";
		$s = (int)@getimagesize($path)[1];
		for ($y = 0; $y < $s; $y++) {
			for ($x = 0; $x < $s; $x++) {
				$colorat = @imagecolorat($img, $x, $y);
				$a = ((~((int)($colorat >> 24))) << 1) & 0xff;
				$r = ($colorat >> 16) & 0xff;
				$g = ($colorat >> 8) & 0xff;
				$b = $colorat & 0xff;
				$skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
			}
		}
    
		@imagedestroy($img);
    
		return new Skin(
			"Custom",
			$skinbytes,
			"",
			"geometry.{$id}",
			file_get_contents(self::$main->getDataFolder() . "texture/{$id}/geometry.json")
		);
	}
  
	public static function createProgressBar(int $progress, int $max): string {
		$bar = "";
		for ($i = 0; $i <= ($max/2) - 1; $i++) { //10 Normal hearts
			$bar .= self::getProgressIcon($progress);
			$progress -= 2;
		}
		return $bar;
	}
  
	public static function getProgressIcon(int $hearts) : string {
		if($hearts >= 2)
			return "§2█§r";
		elseif($hearts === 1)
			return "§6▅§r";
		return "§4▁§r";
	}
  
	public static function addMoney(Player $player, int $coins) : void {
		if (!self::$validEconomy) {
			self::$main->getServer()->getLogger()->warning("$$coins could not be given to $player because of an invalid economy plugin! Check your BloodyEnvoys config.yml and your installed plugins!");
			return;
		}
		
		switch(self::$eco){
			case 'BedrockEconomy':
				BedrockEconomyAPI::legacy()->addToPlayerBalance(
				$player->getName(),
				$coins
				);
			break;

			case 'EconomyAPI':
				EconomyAPI::getInstance()->addMoney($player, $coins);
			break;
		}
		$player->sendPopup(str_replace("{coins}", $coins, self::$main->getEnvoysConfig()->getNested("message.popup.claimed")));
	}
}