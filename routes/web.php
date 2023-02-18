<?php

use Illuminate\Support\Facades\Route;
use App\Libraries\ResponseBase;

Route::fallback(function () {
    $response['message'] = "Oops! The API you're looking for isn't found.";
    return ResponseBase::error($response, 404);
});
