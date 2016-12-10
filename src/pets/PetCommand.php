<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class PetCommand extends PluginCommand {

	public function __construct(main $main, $name) {
		parent::__construct(
				$name, $main
		);
		$this->main = $main;
	}

	public function onCommand(CommandSender $sender,Command $cmd,array $args){
	if(strtolower($cmd->getName() == "pets")){
		if (!isset($args[0])) {
			$sender->sendMessage("§e======PetHelp======");
			$sender->sendMessage("§b/pets spawn [type] to spawn a pet");
			$sender->sendMessage("§b/pets off to set your pet off");
			$sender->sendMessage("§b/pets setname [name] name your pet");
			$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
			return true;
		}
		switch (strtolower($args[0])){
			case "respawn":
				$player = $event->getPlayer();
				$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
				if($data->exists("type")){ 
					$type = $data->get("type");
					$this->changePet($player, $type);
				}
				if($data->exists("name")){ 
					$name = $data->get("name");
					$this->getPet($player->getName())->setNameTag($name);
				}
				return true;
			break;			
			case "name":
			case "setname":
				if (isset($args[1])){
					unset($args[0]);
					$name = implode(" ", $args);
					$this->main->getPet($sender->getName())->setNameTag($name);
					$sender->sendMessage("Set Name to ".$name);
					$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					$data->set("name", $name); 
					$data->save();
				}
				return true;
			break;
			case "help":
				$sender->sendMessage("§e======PetHelp======");
				$sender->sendMessage("§b/pets spawn [type] to spawn a pet");
				$sender->sendMessage("§b/pets off to set your pet off");
				$sender->sendMessage("§b/pets setname [name] name your pet");
				$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
				return true;
			break;
			case "off":
				$this->main->disablePet($sender);
			break;
			case "spawn":
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
							$this->main->changePet($sender, "WolfPet");
							$pettype = "Dog";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Pig":
							$this->main->changePet($sender, "PigPet");
							$pettype = "Pig";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Rabbit":
							$this->main->changePet($sender, "RabbitPet");
							$pettype = "Rabbit";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Cat":
							$this->main->changePet($sender, "OcelotPet");
							$pettype = "Cat";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
						case "Chicken":
							$this->main->changePet($sender, "ChickenPet");
							$pettype = "Chicken";
							$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
							return true;
						break;
					default:
						$sender->sendMessage("§b/pets spawn [type]");
						$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
					break;	
					return true;
					}
				}
			break;
		}
		return true;
	}
	}
}
