![logo](https://github.com/Vqnic/BloodyEnvoys/assets/77890259/8bf0e476-70d3-4de6-aa1d-1fad60d679c9)

# WHAT IS THIS?
This is a simple Envoys plugin which enables you to have random treasure chests spawn in your warzone! Different tiers of envoys drop a different amount of coins! 
### Showcased here: https://www.youtube.com/watch?v=FJWu_CB14QU

## ECONOMY PLUGIN REQUIREMENTS (!!!)
https://poggit.pmmp.io/p/bedrockeconomy
OR
https://poggit.pmmp.io/p/economyapi


## COMES WITH PREMADE (AND EDITABLE) MODELS
NOTE: These models were a commission from before I knew how to make them myself. 
I would link you to the creator, but sadly they have removed their account... They were under the user 'duanworkshop' if you can find them elsewhere.
If you want to see some assets I have made myself, please refer to https://github.com/Vqnic/BloodyModels!


<img src="https://github.com/Vqnic/BloodyEnvoys/assets/77890259/1cdd3612-e497-4b22-a750-aa7c223f8524"  width="400" height="400">

## CONFIG FILE
```
setting:
  world:
    forceload: true

  spawn:
    online: 0 #number of players online to spawn envoys
    cooldown: 1
  economy: "EconomyAPI"

worlds:
  world:
    - displayname: "Tier 1" #name which is shown on top of the envoy
      tier: 1 #determines how big and how much hits an envoy can take
      texture: "tier1" #the folder, see note below
      coinlimit: [700, 1000]
      itemation: "minecraft:stone" #drop effects, leave empty "" to randomize between iron->emerald
      location: [100, 100, 100, 90, 90] #x y z yaw pitch
      
    - displayname: "Some Tier 2 Envoy"
      tier: 2
      texture: "tier2"
      coinlimit: [7000, 1000]
      itemation: "minecraft:spider_eye"
      location: [100, 100, 200, 90, 90]
      
########################################################
# NOTE ON TEXTURE:                                     #
# 1) texture name is now the FOLDER name and inside    #
#    it are texture.png and geometry.json              #
#    example: BloodyEnvoys/texture/tier1/              #
#                - texture.png                         #
#                - geometry.json                       #
# 2) geometry identifier should be the same as texture #
#    example: geometry.tier1                           #
#                                                      #
#########################################################
# Plugin Messages :D                                    #
# Placeholders:                                         #
# {coin} - coin received (for popup only)               #
# {cooldown} - the cooldown                             #
# {time} - time before spawn                            #
# {world} - the world folder                            #
# {online} - needed player                              #
#########################################################
message:
  prefix: "§l§6ENVOYS §8>§r§7>"
  chat:
    spawned: "§gEnvoys have spawned at {world}"
    next_spawn: "§gEnvoys will spawn in §7{time}§g minute(s)!"
    not_enough_players: "§cEnvoys need {online} players to spawn! Next schedule: {cooldown} minute(s)"

  popup:
    claimed: "§g+ §7${coins}§g!"
```
  
[![Chat](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/ADEz9KBAW9)

