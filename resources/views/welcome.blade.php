@extends('layouts.app')

@section('content')
    <section class="join-game">
        <h1>Join Game</h1>
        <form action="{{route('game.join')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Your Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="room_name" class="form-label">Room Name:</label>
                <input type="text" class="form-control" id="room_name" name="room_name" required>
            </div>

            <button type="submit" class="btn btn-primary">Join Game</button>
        </form>
    </section>
@endsection
