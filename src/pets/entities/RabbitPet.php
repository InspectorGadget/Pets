<?php

namespace pets\entities;

class RabbitPet extends Pet {

	const NETWORK_ID = 18;
	
	const TYPE_BROWN = 0;

	public $width = 0.5;
	public $height = 0.5;
	
	public function getName() {
		return "RabbitPet";
	}

	public function getSpeed() {
		return 1.5;
	}
}
