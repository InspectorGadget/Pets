<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\PluginCommand;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

use onebone\economyapi\EconomyAPI;

class PetCommand extends Command {

	public function __construct(){
    		parent::__construct("pets", "pets plugin");
  	}

	public function onCommand(CommandSender $sender , array $args){
		if(!$sender instanceof Player) {
			$sender->sendMessage("Only Players can use this command");
			return true;
		}
		if (!isset($args[0])) {
			$sender->sendMessage("§e======§6 Pet Help Page §e======");
			$sender->sendMessage("§b/pets spawn [type] to spawn a pet");
			$sender->sendMessage("§b/pets off to set your pet off");
			$sender->sendMessage("§b/pets setname [name] name your pet");
			$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
			return true;
		}
		switch (strtolower($args[0])){
			case "respawn":
				if($sender->hasPermission('pet.command.respawn')) {
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
				}
			break;			
			case "name":
			case "setname":
				if($sender->hasPermission('pet.command.setname')) {
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
				}
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
				if($sender->hasPermission('pet.command.off')) {
					$this->main->disablePet($sender);
				}
			break;
			case "spawn":
				if($sender->hasPermission('pet.command.spawn')) {
				if (isset($args[1])){
					switch ($args[1]){
						case "Dog":
							if($r = EconomyAPI::getInstance()->reduceMoney($sender, 1500)){
								# Cool, everything is fine.
								$this->main->changePet($sender, "WolfPet");
								$pettype = "Dog";
								$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
								return true;
							} else {
								// $r is an error code
								switch($r){
									case EconomyAPI::RET_INVALID:
									# Invalid $amount
									break;
									case EconomyAPI::RET_CANCELLED:
									# Transaction was cancelled for some reason :/
									break;
									case EconomyAPI::RET_NO_ACCOUNT:
									# Player wasn't recognised by EconomyAPI aka. not registered
									break;
								}
							}
						break;
						case "Pig":
							if($r = EconomyAPI::getInstance()->reduceMoney($sender, 750)){
								# Cool, everything is fine.
								$this->main->changePet($sender, "PigPet");
								$pettype = "Pig";
								$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
								return true;
							} else {
								// $r is an error code
								switch($r){
									case EconomyAPI::RET_INVALID:
									# Invalid $amount
									break;
									case EconomyAPI::RET_CANCELLED:
									# Transaction was cancelled for some reason :/
									break;
									case EconomyAPI::RET_NO_ACCOUNT:
									# Player wasn't recognised by EconomyAPI aka. not registered
									break;
								}
							}
						break;
						case "Rabbit":
							if($r = EconomyAPI::getInstance()->reduceMoney($sender, 1000)){
								# Cool, everything is fine.
								$this->main->changePet($sender, "RabbitPet");
								$pettype = "Rabbit";
								$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
								return true;
							} else {
								// $r is an error code
								switch($r){
									case EconomyAPI::RET_INVALID:
									# Invalid $amount
									break;
									case EconomyAPI::RET_CANCELLED:
									# Transaction was cancelled for some reason :/
									break;
									case EconomyAPI::RET_NO_ACCOUNT:
									# Player wasn't recognised by EconomyAPI aka. not registered
									break;
								}
							}
						case "Cat":
							if($r = EconomyAPI::getInstance()->reduceMoney($sender, 1500)){
								# Cool, everything is fine.
								$this->main->changePet($sender, "OcelotPet");
								$pettype = "Cat";
								$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
								return true;
							} else {
								// $r is an error code
								switch($r){
									case EconomyAPI::RET_INVALID:
									# Invalid $amount
									break;
									case EconomyAPI::RET_CANCELLED:
									# Transaction was cancelled for some reason :/
									break;
									case EconomyAPI::RET_NO_ACCOUNT:
									# Player wasn't recognised by EconomyAPI aka. not registered
									break;
								}
							}
						break;
						case "Chicken":
							if($r = EconomyAPI::getInstance()->reduceMoney($sender, 750)){
								# Cool, everything is fine.
								$this->main->changePet($sender, "ChickenPet");
								$pettype = "Chicken";
								$sender->sendMessage($this->main->getConfig()->get("PetCreateMessage"));
								return true;
							} else {
								// $r is an error code
								switch($r){
									case EconomyAPI::RET_INVALID:
									# Invalid $amount
									break;
									case EconomyAPI::RET_CANCELLED:
									# Transaction was cancelled for some reason :/
									break;
									case EconomyAPI::RET_NO_ACCOUNT:
									# Player wasn't recognised by EconomyAPI aka. not registered
									break;
								}
							}
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
