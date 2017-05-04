<?php

namespace pets;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;

class PetCommand extends PluginCommand {
	
	/** @var main|Plugin */
	private $ownerplugin;

	public function __construct(Plugin $plugin) {
		parent::__construct("pets", $plugin);
		$this->setAliases([]);
		$this->setPermission("all");
		$this->setDescription("Command for Pets ~ Your lovely companions");
		$this->ownerplugin = $plugin;
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if (!$sender instanceof Player) {
			$sender->sendMessage("Only Players can use this command");
			return true;
		}
		if (!isset($args[0])) {
			$args[0] = "help";
		}
		switch (strtolower($args[0])) {
			case "respawn":
				if ($sender->hasPermission('pet.command.respawn')) {
					$data = new Config($this->ownerplugin->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
					if ($data->exists("type")) {
						$type = $data->get("type");
						$this->ownerplugin->changePet($sender, $type);
					}
					if ($data->exists("name")) {
						$name = $data->get("name");
						$this->ownerplugin->getPet($sender->getName())->setNameTag($name);
					}
					return true;
				}
				break;
			case "name":
			case "setname":
				if ($sender->hasPermission('pet.command.setname')) {
					if (isset($args[1])) {
						unset($args[0]);
						$name = implode(" ", $args);
						$this->ownerplugin->getPet($sender->getName())->setNameTag($name);
						$this->ownerplugin->getPet($sender->getName())->setNameTagAlwaysVisible(true);
						$this->ownerplugin->getPet($sender->getName())->setNameTagVisible(true);
						$sender->sendMessage("Set Name to " . $name);
						$data = new Config($this->ownerplugin->getDataFolder() . "players/" . strtolower($sender->getName()) . ".yml", Config::YAML);
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
				$sender->sendMessage("§b/pets respawn - spawns it again");
				$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
				return true;
				break;
			case "off":
				if ($sender->hasPermission('pet.command.off')) {
					$this->ownerplugin->disablePet($sender);
				}
				break;
			case "spawn":
				if ($sender->hasPermission('pet.command.spawn')) {
					if (isset($args[1])) {
						switch ($args[1]) {
							case "Dog":
								if ($r = EconomyAPI::getInstance()->reduceMoney($sender, 1500)) {
									# Cool, everything is fine.
									$this->ownerplugin->changePet($sender, "WolfPet");
									$pettype = "Dog";
									$sender->sendMessage(sprintf($this->ownerplugin->getConfig()->get("PetCreateMessage"), $pettype));
									return true;
								} else {
									switch ($r) {
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
								if ($r = EconomyAPI::getInstance()->reduceMoney($sender, 750)) {
									# Cool, everything is fine.
									$this->ownerplugin->changePet($sender, "PigPet");
									$pettype = "Pig";
									$sender->sendMessage(sprintf($this->ownerplugin->getConfig()->get("PetCreateMessage"), $pettype));
									return true;
								} else {
									// $r is an error code
									switch ($r) {
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
								if ($r = EconomyAPI::getInstance()->reduceMoney($sender, 1000)) {
									# Cool, everything is fine.
									$this->ownerplugin->changePet($sender, "RabbitPet");
									$pettype = "Rabbit";
									$sender->sendMessage(sprintf($this->ownerplugin->getConfig()->get("PetCreateMessage"), $pettype));
									return true;
								} else {
									// $r is an error code
									switch ($r) {
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
							case "Cat":
								if ($r = EconomyAPI::getInstance()->reduceMoney($sender, 1500)) {
									# Cool, everything is fine.
									$this->ownerplugin->changePet($sender, "OcelotPet");
									$pettype = "Cat";
									$sender->sendMessage(sprintf($this->ownerplugin->getConfig()->get("PetCreateMessage"), $pettype));
									return true;
								} else {
									// $r is an error code
									switch ($r) {
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
								if ($r = EconomyAPI::getInstance()->reduceMoney($sender, 750)) {
									# Cool, everything is fine.
									$this->ownerplugin->changePet($sender, "ChickenPet");
									$pettype = "Chicken";
									$sender->sendMessage(sprintf($this->ownerplugin->getConfig()->get("PetCreateMessage"), $pettype));
									return true;
								} else {
									// $r is an error code
									switch ($r) {
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
							default:
								$sender->sendMessage("§b/pets spawn [type]");
								$sender->sendMessage("§bEnabledTypes: Dog, Rabbit, Pig, Cat, Chicken");
								break;
						}
					}
					break;
				}
				return true;
		}
		return false;
	}
}
