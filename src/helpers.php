<?php

use Laravolt\Avatar\Avatar;

if (!function_exists('getChatUser')) {
    function getChatUser()
    {
        dd(auth()->user());
    }
}


if (!function_exists('messengerJs')) {
    function messengerJs()
    {
        return '<script type="text/javascript" src="' . asset('vendor/laravel-messenger/js/chat.js') . '"></script>';
    }
}

if (!function_exists('messengerCss')) {
    function messengerCss()
    {
        return '<link rel="stylesheet" type="text/css" href="' . asset('vendor/laravel-messenger/css/chat.css') . '"/>';
    }
}


if (!function_exists('messengerRoutes')) {
    function messengerRoutes()
    {
        $routes = [
            'sendMessage' => route('laravel.messenger.send.message'),
            'loadChatMessages' => route('laravel.messenger.load.messages'),
            'fetchNewMessage' => route('laravel.messenger.fetch.new.message'),
            'createNewChat' => route('laravel.messenger.create.chat')
        ];

        return "<script> var messengerRoutes = " . json_encode($routes) . "</script>";
    }
}


if (!function_exists('getErrorResponse')) {

    function getErrorResponse($message = "Something went wrong. Please try again later")
    {
        return [
            'success' => false,
            'message' => $message
        ];
    }
}


if (!function_exists('getUserAvatar')) {

    function getUserAvatar($name)
    {
        return (new Avatar())->create($name)->toBase64();
    }
}
