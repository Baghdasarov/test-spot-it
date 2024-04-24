@extends('layouts.app')

@section('content')
    <section class="container mt-4">
        <div class="card">
            <div class="card-header flex">
                <div class="col-md-6">
                    <p>Your ID: {{$playerId}}</p>
                    <p>Game Room: {{$game->room->name}}</p>
                    <p>Current State: {{$game->game_state ?? 'N/A'}}</p>
                    <p>Game Started: {{$game->created_at->toFormattedDateString()}}</p>
                </div>
                <div class="col-md-6">
                    <p>Players in the room</p>
                    @foreach($game->room->players as $player)
                        <p>{{$player->username}}</p>
                    @endforeach
                </div>
            </div>
        </div>
        <hr />
        <div class="card-body flex">
            <div id="card1" class="col-md-5" data-cards="{{json_encode([...$cards])}}">
                <h4>Card 1</h4>
                @foreach($cards[0] as $card1)
                    <img class="card-item" src="{{asset('images/'.$card1.'.png')}}" alt="Card 1" data-id="{{$card1}}">
                @endforeach
            </div>
            <div id="card2" class="col-md-5" data-comarecard="{{json_encode($cards[0])}}">
                <h4>Card 2</h4>
                @foreach($cards[1] as $card2)
                    <img class="card-item" src="{{asset('images/'.$card2.'.png')}}" alt="Card 1" data-id="{{$card2}}">
                @endforeach
            </div>
        </div>

        <script>
              function sendWinner() {
                fetch(`/game/{{$game->id}}`, {
                  method: 'PUT',
                  headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                  },
                  body: JSON.stringify({
                    winner_id: "{{$playerId}}"
                  })
                })
                  .then(response => response.json())
                  .then(data => {
                    console.log('Success:', data);
                    alert(data.message);  // Display success message
                  })
                  .catch((error) => {
                    console.error('Error:', error);
                  });
              }

            function detectWinner(currentCardId) {
                const cards = $("#card1").data('cards');
                let repeat = 0;
                cards.forEach(function (items) {
                  items.forEach(function (item) {
                    if (item === currentCardId) {
                      repeat = repeat + 1;
                    }
                  })
                })

                if (repeat === 2) {
                  sendWinner()
                } else {
                  alert('Try again')
                }
            }

            $(document).on('click', '#card1 .card-item', function () {
              const currentCardId = $(this).data('id');
              detectWinner(currentCardId);
            })

            $(document).on('click', '#card2 .card-item', function () {
              const currentCardId = $(this).data('id');
              detectWinner(currentCardId);
            })

              $(document).ready(function () {

                window.Echo.channel('game-channel')
                  .listen('.game.event', (e) => {
                    if (e.data.status === 1) {
                      location.reload();
                    }
                    console.log(e);
                  });
              })
        </script>
    </section>
@endsection
