<?php

namespace Vanic\Bloody\Envoys;

use pocketmine\entity\Skin;

class Utils {
  
  private static Main $main;
  
  public static function getEnvoySkin(int $tier) : Skin {
    self::$main = Main::getInstance();
    
    //Definitely ->  NOT  <- my code here. (Heh.) This bit was borrowed from a public plugin.
    $path = self::$main->getDataFolder() . "Envoy_$tier.png";
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
    
    return new Skin("Custom", $skinbytes, "", "geometry.Envoy", file_get_contents(self::$main::getInstance()->getDataFolder() . "Envoy_$tier.json"));
  }
  
  public static function createProgressBar(int $progress, int $max): string {
    $bar = "";
    for ($i = 0; $i <= ($max/2) - 1; $i++) { //10 Normal hearts
      $bar .= self::getProgressIcon($progress);
      $progress -= 2;
    }
    return $bar;
  }
  
  public static function getProgressIcon(int $hearts) : string{
    if($hearts >= 2)
      return "§2█§r";
    elseif($hearts === 1)
      return "§6▅§r";
    return "§4▁§r";
  }
}