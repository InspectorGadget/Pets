<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pets\main;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use onebone\economyapi\EconomyAPI;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;

class PetCommand extends Command {

	public function __construct(){
		parent::__construct("pets", "pets plugin");
	}

	public function execute(CommandSender $sender, $label, array $args){
		$this->main = main::getInstance();
		if($sender->hasPermission("pets.command") && $sender instanceof Player){
			if (!isset($args[0])) {
				$sender->sendMessage("§e======§6 Pet Help Page §e======");
				$sender->sendMessage("§b/pets spawn [type] to spawn a pet");
				$sender->sendMessage("§b/pets off to set your pet off");
				$sender->sendMessage("§b/pets setname [name] name your pet");
				$sender->sendMessage("§bEnabled Types: dog, rabbit, pig, cat, chicken");
				return true;
			}
			if($args[0] === "respawn"){
				if($sender->hasPermission('pet.command.respawn')) {
					$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					if($data->exists("type")){
						$type = $data->get("type");
						$this->main->changePet($sender, $type);
					}
					if($data->exists("name")){
						$name = $data->get("name");
						$this->main->getPet($sender->getName())->setNameTag($name);
						$this->main->getPet($sender->getName())->setNameTagVisible(true);
						$this->main->getPet($sender->getName())->setNameTagAlwaysVisible(true);
					}
				}
			}elseif ($args[0] === "setname"){
				if($sender->hasPermission('pet.command.setname')) {
					if (isset($args[1])){
						unset($args[0]);
						$name = implode(" ", $args);
						$this->main->getPet($sender->getName())->setNameTag($name);
						$this->main->getPet($sender->getName())->setNameTagVisible(true);
						$this->main->getPet($sender->getName())->setNameTagAlwaysVisible(true);
						$sender->sendMessage("Set Name to ".$name);
						$data = new Config($this->main->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
						$data->set("name", $name);
						$data->save();
					}
				}
			}elseif ($args[0] === "help"){
				$sender->sendMessage("§e======PetHelp======");
				$sender->sendMessage("§b/pets spawn [type] to spawn a pet");
				$sender->sendMessage("§b/pets off to set your pet off");
				$sender->sendMessage("§b/pets setname [name] name your pet");
				$sender->sendMessage("§bEnabledTypes: dog, rabbit, pig, cat, chicken");
			}elseif ($args[0] === "off"){
				if($sender->hasPermission('pet.command.off')) {
					$this->main->disablePet($sender);
				}
			}elseif ($args[0] === "spawn"){
				if($sender->hasPermission('pet.command.spawn')) {
					if ($args[1] === "dog"){
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
								$sender->sendMessage("§cYou dont have enought money");
								break;
								case EconomyAPI::RET_NO_ACCOUNT:
								# Player wasn't recognised by EconomyAPI aka. not registered
								break;
							}
						}
					}elseif ($args[1] === "pig"){
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
								$sender->sendMessage("§cYou dont have enought money");
								break;
								case EconomyAPI::RET_NO_ACCOUNT:
								# Player wasn't recognised by EconomyAPI aka. not registered
								break;
							}
						}
					}elseif ($args[1] === "rabbit"){
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
								$sender->sendMessage("§cYou dont have enought money");
								break;
								case EconomyAPI::RET_NO_ACCOUNT:
								# Player wasn't recognised by EconomyAPI aka. not registered
								break;
							}
						}
					}elseif ($args[1] === "cat"){
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
								$sender->sendMessage("§cYou dont have enought money");
								break;
								case EconomyAPI::RET_NO_ACCOUNT:
								# Player wasn't recognised by EconomyAPI aka. not registered
								break;
							}
						}
					}elseif ($args[1] === "chicken"){
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
								$sender->sendMessage("§cYou dont have enought money");
								break;
								case EconomyAPI::RET_NO_ACCOUNT:
								# Player wasn't recognised by EconomyAPI aka. not registered
								break;
							}
						}
					}
				}
			}
		}
	}
}
