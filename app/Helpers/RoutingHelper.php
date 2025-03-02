<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class RoutingHelper
{
    public static function isDashboardRoute() {
        return Route::is('admin.dashboard');
    }

    public static function isCategoryRoute() {
        return self::isCategoryEdit() || self::isCategoryCreate() || self::isCategoryIndex();
    }

    public static function isCategoryCreate() {
        return Route::is(['admin.categories.create']);
    }

    public static function isCategoryIndex() {
        return Route::is(['admin.categories.index']);
    }
    public static function isCategoryEdit() {
        return Route::is(['admin.categories.edit']);
    }
}
