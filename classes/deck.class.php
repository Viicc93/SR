<?php
class Deck
{
  /**
   * Card array
   *
   * @var array
   */
    public $_cards;
 // public $_cardsOnTable;
    public $_backOfCard;
    public $_users;
    //public $_card;
    public $_usersId;
    public $_thrownCards;
    public $playerTurn;
    public $newSuit;
    public $winner;
    public $isEight;
    public $availableCards;

    public $drawCard;




  /**
   * The constructor define $_cards array,
   *  _cardsOnTable and _users arrays.
   * It calls addBotPlayer() method to
   * instantiate Bot-class.
   */
    public function __construct()
    {
        // Define cards array
        $this->_cards = [];
        // Define users array
        $this->_users = [];
        // Define thrown cards array
        $this->_thrownCards = [];
        $this->availableCards = [];
        // Define is eight property
        $this->isEight = null;
        // Define player turn property
        $this->playerTurn = 0;
        // Add Bot player
        $this->addBotPlayer();

        $this->drawCard = false;

    }


  ########################################################################
  # PUBLIC METHODS                                                       #
  ########################################################################
  /**
   * Manipulate $_cards array with card object.
   * This method uses composition to manipulate
   * $_cards array with all card objects.
   *
   *  @param object  Card  A card object
   */
    public function setCards(Card $card)
    {
        array_push($this->_cards, $card);
    }


    public function getCards()
    {
        return $this->_cards;
    }


  /**
   * Adding players to the game.
   *
   * This method use composition to add the plyaer,
   * and then push them to _users array.
   * It is calling dealCardToPlayers() method also
   * to deal 8 cards to every user.
   *
   * @param object  require user object.
   */
    public function addPlayers(User $user)
    {
        if (count($this->_users) < 4) {
            // push user object to _users array
            array_push($this->_users, $user);
        }
        // call dealCardToPlayers method
        $this->dealCardToPlayers();
    }


/* ==========================================================================
   BOT Player
   ========================================================================== */

  /**
   * botPlayCard() is the method that will be called
   * when Bot turn
   *
   */
    public function botPlayCard()
    {
      // Check if $playerTurn property is set to 0
      if ($this->getPlayerTurn() == 0 && !empty($this->_users[0]->_cardsOnHand)) {
        // find the last played card
        $latestCard = end($this->_thrownCards);


        // Loop through Bot hand
        for ($i=0; $i < count($this->_users[0]->_cardsOnHand); $i++) {
            // Check if there are cards that match the last thrown card
          if($this->_users[0]->_cardsOnHand[$i] != null){
            if ($this->_users[0]->_cardsOnHand[$i]->getCardValue() == $latestCard->getCardValue() ||
                $this->_users[0]->_cardsOnHand[$i]->getCardSuit() == $latestCard->getCardSuit()) {
              // Push all match cards to $availableCards array
              array_push($this->availableCards, array_splice($this->_users[0]->_cardsOnHand, $i, 1)[0]);

            }
          }
        }

      if (empty($this->availableCards)) {
        $this->drawCard(0);
      }
      else {
          // Move a object in $this->availableCards array to thrown cards array
          array_push($this->_thrownCards, array_pop($this->availableCards));

          for ($i=0; $i < count($this->availableCards); $i++) {

          array_push($this->_users[0]->_cardsOnHand, array_pop($this->availableCards));
        }
        $this->nextPlayer();
      }
          // move not played cards back to hand
    }
    elseif(empty($this->_users[0]->_cardsOnHand)){
      $this->winner = true;
      return $this->winner;
    }
    // Set $playerTurn property to the next player
   //$this->nextPlayer();
        //if ($this->isEight() === true) return false;
  }


    public function addBotPlayer()
    {
        array_push($this->_users, new Bot());
    }

  /**
   * Get Bot player
   *
   *
   */
    public function getBotPlayer()
    {
        return $this->getUser()[0];
    }

/* ************************************************************************************************************************************** */



  /*
  * Dealing 8 cards to every plyaer. This method
  * will be called from class's constructor when we
  * instantiate deck-object.
  * It loop through _users array and then suffle
  * _cards array 8 times and then pop an card-item and push it in
  * _cardsOnHand array that located in Player-class.
  */
    public function dealCardToPlayers()
    {
        // shuffle _cards array
        shuffle($this->_cards);
        // loop through _users array
        for ($i=0; $i < count($this->_users); $i++) {
            for ($j=0; $j < 8; $j++) {
                if (count($this->_users[$i]->_cardsOnHand) < 8) {
                    // pop a card-item and push it into _cardsOnHand array
                    array_push($this->_users[$i]->_cardsOnHand, array_pop($this->_cards));
                }
            }
        }
    }

    public function showCardsOnHand()
    {

        for ($i=0; $i < count($this->_users); $i++) {
            $cards = $this->_users[$i]->getCardsArray();
        }
        return $cards;
    }

  /**
   * getUserId() method is a method that looping through
   * user objects, and by using getUserId() method which is
   * in user object, it will return the array _userId.
   */
    public function getUserId()
    {
        // define an array to hold user ids
        $this->_usersId = [];
        // loop through users object
        for ($i=0; $i < count($this->_users); $i++) {
            // push user ids to $_userId array
            array_push($this->_usersId, $this->_users[$i]->getUserId());
        }
        // return _usersId array
        return $this->_usersId;
    }


    /**
     * Return all users
     *
     *
     */
    public function getUser()
    {
        return $this->_users;
    }



    public function countUsers()
    {
        return count($this->_users);
    }


  // public function moveCardFromDeck($cardIndex){
  //   array_splice($this->_cards, $cardIndex, 1);
  // }
    public function renderDeck($_backOfCard)
    {
        return $this->_backOfCard = $_backOfCard;
    }


/**
 * @param $cardId
 *
 *
 */

    public function playCard($cardId, $playerIndex) {
    if (!empty($this->_users[$playerIndex]->_cardsOnHand)) {

      $this->checkAvailabeCard($playerIndex);

      if($this->drawCard == true){
        $this->drawCard($playerIndex);
      }
      else {
        $playerHand = $this->_users[$playerIndex]->_cardsOnHand;
        $latestCard = end($this->_thrownCards);

           foreach ($playerHand as $i => $card) {
             if ($card->getCardId() == $cardId) {
               $playedCard = array_splice($this->_users[$playerIndex]->_cardsOnHand, $i, 1)[0];

                 if ($playedCard->getCardValue() == $latestCard->getCardValue() ||  $playedCard->getCardSuit() == $latestCard->getCardSuit()) {
                     array_push($this->_thrownCards, $playedCard);
                     $this->nextPlayer();
                 }
             }
            }
         }
      //return $this->_users[$playerIndex]->_cardsOnHand;
      }
      elseif(empty($this->_users[$playerIndex]->_cardsOnHand)){
       $this->winner = true;
      return $this->winner;
      }
    }


public function checkAvailabeCard($index)
    {
      // Get card on hand for the user that has the turn
      $userHand = $this->_users[$index]->_cardsOnHand;

      // Get thrownCards array
      $latestCard = end($this->_thrownCards);

      foreach ($userHand as $i => $card){
        if ($card->getCardId() == $latestCard->getCardId() || $latestCard->getCardSuit() == $card->getCardSuit()) {
          if (isset($this->_users[$index]->_cardsOnHand[$i])) {
             array_push($this->availableCards, array_splice($this->_users[$index]->_cardsOnHand, $i, 1)[0]);
          }

        }
      }

     if (empty($this->availableCards)) {
        $this->drawCard = true;
      }
      else {
        for ($i=0; $i < count($this->availableCards); $i++) {

          array_push($this->_users[$index]->_cardsOnHand, array_pop($this->availableCards));
        }
      }

    }


public function drawCard($index){
    $drawnCard = end($this->_cards);
    $latestCard = end($this->_thrownCards);

    if ($drawnCard->getCardValue() == $latestCard->getCardValue() || $drawnCard->getCardSuit() == $latestCard->getCardSuit()) {
       array_push($this->_thrownCards, array_pop($this->_cards));
       $this->drawCard = false;
       $this->nextPlayer();
    }
    else {
      array_push($this->_users[$index]->_cardsOnHand, array_pop($this->_cards));
      $this->drawCard = false;
      $this->nextPlayer();
    }
  }


    // public function findCard($cardId, $userIndex)
    // {
    //     $userCardHand = $this->_users[$userIndex]->getCardsArray();
    //     for ($i=0; $i < count($userCardHand); $i++) {
    //         if ($cardId == $userCardHand[$i]->getCardId()) {
    //             return array_splice($userCardHand, $i, 1)[0];
    //         }
    //     }
    //     // foreach ($cardsOnhand as $i => $card) {
    //     //     if ($card->getCardId() == $cardId) {
    //     //         //$card = array_splice($cardsOnhand, $i, 1);
    //     //         //return $card;
    //     //         return array_splice($cardsOnhand, $i, 1);
    //     //     }
    //     // }
    //     //print_r($cardsOnhand);
    // }


    public function getCardOnTable()
    {
        shuffle($this->_cards);
        return $this->_cards;
    }


    /**
     * Get first thrown card
     *
     *
     */

    public function startCard()
    {
         /*  IF no card to start with get one */
        if (empty($this->_thrownCards)) {
            array_push($this->_thrownCards, array_pop($this->_cards));
        }
    }

    public function getThrownCard()
    {
        return $this->_thrownCards;
    }

    /**
     * return user object that hast the turn to play
     *
     *
     */
    public function nextPlayer()
    {

        $this->playerTurn == ($this->countUsers() - 1) ? $this->playerTurn = 0 : $this->playerTurn++;
        //return $this->_users[$this->getNextPlayerIndex()];
    }

    /**
     * return the index of the player that has the turn to play
     *
     *
     */
    public function getPlayerTurn()
    {
        return $this->playerTurn;
    }

    /**
     * set user index that will get the turn to play
     *
     *
     */
    public function setNextPlayerIndex($n)
    {
        $this->playerTurn = $n;
    }

    public function isEight()
    {
        return $this->isEight;
    }



    /**
     * Get $drawCard
     *
     *
     */
    public function getDrawCard()
    {
      return $this->drawCard;
    }
}
