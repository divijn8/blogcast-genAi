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
    public static function isTagRoute() {
        return self::isTagEdit() || self::isTagCreate() || self::isTagIndex();
    }

    public static function isTagCreate() {
        return Route::is(['admin.tags.create']);
    }

    public static function isTagIndex() {
        return Route::is(['admin.tags.index']);
    }

    public static function isTagEdit() {
        return Route::is(['admin.tags.edit']);
    }

    public static function isPostRoute() {
        return self::isPostEdit() || self::isPostCreate() || self::isPostIndex() || self::isPostDraft();
    }

    public static function isPostCreate() {
        return Route::is(['admin.posts.create']);
    }

    public static function isPostIndex() {
        return Route::is(['admin.posts.index']);
    }

    public static function isPostEdit() {
        return Route::is(['admin.posts.edit']);
    }

    public static function isPostDraft() {
        return Route::is(['admin.posts.draft']);
    }
}
