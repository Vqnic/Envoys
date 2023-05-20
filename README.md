![image](https://i.imgur.com/orRM0Ao.png)

# ENVOYS PLUGIN

## REQUIREMENTS
https://poggit.pmmp.io/p/bedrockeconomy


## COMES WITH PREMADE (AND EDITABLE) MODELS
![Envoy_1](https://github.com/Vqnic/BloodyEnvoys/assets/77890259/1cdd3612-e497-4b22-a750-aa7c223f8524)

## CONFIG FILE
```
time-to-spawn: 30  #How much time, in minutes, should it take for envoys to spawn?

online-to-spawn: 0 #How many players need to be online for envoys to spawn? Set to zero to disable!

world: "KitPVP" #Which world should the envoys spawn in?

#TIER I ENVOY
min-coins-t1: 12500
max-coins-t1: 125000
#TIER II ENVOY
min-coins-t2: 25000
max-coins-t2: 250000
#TIER III ENVOY
min-coins-t3: 50000
max-coins-t3: 500000
#TIER IV ENVOY
min-coins-t4: 100000
max-coins-t4: 1000000


#Plugin Messages :D
#Placeholders are {coins} and {time}!
prefix: "§l§6ENVOYS §8>§r§7> "
envoys-spawned: "§gEnvoys have spawned!"
envoy-claimed: "§g+ §7${coins}§g!" #POPUP, NOT MESSAGE!!!
envoys-timer: "§gEnvoys will spawn in §7{time}§g minutes!"
envoys-not-enough-players: "&o§cThere aren't enough players for envoys to spawn!"
```

## LOCATIONS FILE
```
#YOU CAN ADD AS MANY AS YOU LIKE! :D
example-envoy:
  x: 100
  y: 100
  z: 100
  yaw: 90
  pitch: 90
example-envoy2:
  x: -100
  y: 100
  z: 100
  yaw: 90
  pitch: 90
example-envoy3:
  x: 100
  y: 100
  z: -100
  yaw: 90
  pitch: 90
example-envoy4:
  x: -100
  y: 100
  z: -100
  yaw: 90
  pitch: 90
  ```
