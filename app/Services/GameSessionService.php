<?php

namespace App\Services;

class GameSessionService
{
    public function generateCards()
    {
        $initCards = [[3, 4, 6], [5, 6, 7], [2, 4, 7], [1, 2, 6], [2, 3, 5], [1, 4, 5], [1, 3, 7]];
        $keys = array_rand($initCards, 2);

        return [
            $initCards[$keys[0]],
            $initCards[$keys[1]]
        ];
    }

}
