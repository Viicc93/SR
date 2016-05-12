<?php
class Bot extends Player {

	function __construct(){
		Parent::__construct("Anna");
	}

	public function DrawCard(){
		# code for player to draw a card when no match on hand....
	}
	public function RenderCard(){
		# code to render cards on hand...
	}
	public function getBotCards()
	{
		return $this->getCardsArray();
	}

	// return Bot object
	public function getBotObj()
	{
		return $this;
	}
}
