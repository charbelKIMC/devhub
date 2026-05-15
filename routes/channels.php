<?php

use Illuminate\Support\Facades\Broadcast;

// Canal conversation privé
Broadcast::channel('conversation.{convId}', function ($user, $convId) {
    return $user->conversations()->where('id', $convId)->exists();
});

// Canal notifications privé
Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});