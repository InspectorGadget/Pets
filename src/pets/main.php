<?php

namespace pets;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class main extends PluginBase implements Listener {

	/** @var  Entity[] */
	public static $pet;
	public static $petState;
	public static $isPetChanging;
	public static $type;
	public $petType;
	public $wishPet;

	public function onEnable() {
		@mkdir($this->getDataFolder());
		@mkdir($this->getDataFolder() . "players");
		$server = Server::getInstance();
		Entity::registerEntity(OcelotPet::class);
		Entity::registerEntity(WolfPet::class);
		Entity::registerEntity(PigPet::class);
		Entity::registerEntity(RabbitPet::class);
		Entity::registerEntity(ChickenPet::class);
		$this->saveDefaultConfig();
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "Pets Has Been Enabled.");
		$this->getServer()->getLogger()->info(TextFormat::BLUE . "By: Driesboiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii");
		$this->getServer()->getCommandMap()->register(PetCommand::class, new PetCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->disablePet($player);
	}

	public function disablePet(Player $player) {
		if (isset(self::$pet[$player->getName()])) {
			self::$pet[$player->getName()]->close();
			unset(self::$pet[$player->getName()]);
		}
	}

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if ($data->exists("type")) {
			$type = $data->get("type");
			$this->changePet($player, $type);
		}
		if ($data->exists("name")) {
			$name = $data->get("name");
			$this->getPet($player->getName())->setNameTag($name);
		}
	}

	public function changePet(Player $player, $newtype) {
		$type = $newtype;
		$this->disablePet($player);
		self::$pet[$player->getName()] = $this->createPet($player, $newtype);
	}

	public function createPet(Player $player, $type, $holdType = "") {
		if (isset(self::$pet[$player->getName()]) != true) {
			$len = rand(8, 12);
			$x = (-sin(deg2rad($player->yaw))) * $len + $player->getX();
			$z = cos(deg2rad($player->yaw)) * $len + $player->getZ();
			$y = $player->getLevel()->getHighestBlockAt($x, $z);

			$source = new Position($x, $y + 2, $z, $player->getLevel());
			if (isset(self::$type[$player->getName()])) {
				$type = self::$type[$player->getName()];
			}
			switch ($type) {
				case "WolfPet":
					break;
				case "RabbitPet":
					break;
				case "PigPet":
					break;
				case "OcelotPet":
					break;
				case "ChickenPet":
					break;
				default:
					$pets = array("OcelotPet", "PigPet", "WolfPet", "RabbitPet", "ChickenPet");
					$type = $pets[rand(0, 5)];
			}
			$pet = $this->create($player, $type, $source);
			return $pet;
		}
	}

	public function create(Player $player, $type, Position $source, ...$args) {
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $source->x),
				new DoubleTag("", $source->y),
				new DoubleTag("", $source->z)
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", $source instanceof Location ? $source->yaw : 0),
				new FloatTag("", $source instanceof Location ? $source->pitch : 0)
			]),
		]);
		/** @var Pets $pet */
		$pet = Entity::createEntity($type, $player->getLevel(), $nbt, ...$args);
		$data = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()) . ".yml", Config::YAML);
		$data->set("type", $type);
		$data->save();
		$pet->setOwner($player);
		$pet->spawnToAll();
		$pet->setNameTagAlwaysVisible(true);
		$pet->setNameTagVisible(true);
		return $pet;
	}

	/** @return Entity */
	public function getPet($player) {
		return self::$pet[$player];
	}
}
