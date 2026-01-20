<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    // Solo permitir si el usuario pertenece al team
    return $user->teams()->where('id', $teamId)->exists();
});

Broadcast::channel('team.{teamId}.notifications', function ($user, $teamId) {
    //return $user->teams()->where('id', $teamId)->exists();
    return true;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});