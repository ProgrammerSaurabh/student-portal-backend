<?php

return [
    'url' => env('FRONTEND_URL'),
    'forgot-password' => env('FRONTEND_URL') . '/forgot-password/:token/reset?email=:email'
];
