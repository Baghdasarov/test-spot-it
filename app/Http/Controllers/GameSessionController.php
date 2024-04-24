<?php

namespace App\Http\Controllers;

use App\Events\GameEvent;
use App\Events\GameUpdated;
use App\Http\Requests\GameSession\JoinRequest;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\Room;
use App\Models\RoomPlayer;
use App\Services\GameSessionService;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function join(JoinRequest $request)
    {
        $player = Player::firstOrCreate([
            'username' => $request->post('username'),
        ]);

        $request->session()->put('player_id', $player->id);

        $room = Room::firstOrCreate([
            'name' => $request->post('room_name'),
        ]);

        $room->players()->attach($player->id);

        $gameSession = GameSession::where('room_id', $room->id)->first();

        if (!$gameSession) {
            $gameSession = GameSession::query()->create(['room_id' => $room->id]);
        }

        return redirect()->route('game.show', $gameSession->id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GameSession  $game
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show(Request $request, GameSession $game, GameSessionService $gameSessionService)
    {
        $player_id = session()->get('player_id');

        if (!$player_id) {
            return redirect()->route('game.index');
        }

        if ($game->game_state) {
            $cards = json_decode($game->game_state);
        } else {
            $cards = $gameSessionService->generateCards();

            $game->game_state = json_encode($cards);
            $game->save();
        }

        return view('game.show', compact('game', 'cards'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function edit(GameSession $gameSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GameSession  $game
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, GameSession $game)
    {
        if ($game->winner_id) {
            return response()->json(['message' => 'Sorry, the game is already won.'], 422);
        }

        $game->winner_id = null;
        $game->game_state = null;
        $game->save();

        broadcast(new GameEvent([
            'game' => $game,
            'status' => 1 // finished
        ]));

        return response()->json(['message' => 'Congratulations! You won!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(GameSession $gameSession)
    {
        //
    }
}
