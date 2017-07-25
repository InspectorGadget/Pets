<?php

namespace pets;

class WhiteRabbitPet extends Pets {

	const NETWORK_ID = 18;
	
	const TYPE_WHITE = 1;

	public $width = 0.5;
	public $height = 0.5;
	
	public function getName() {
		return "WhiteRabbitPet";
	}

	public function getSpeed() {
		return 1.5;
	}
}
