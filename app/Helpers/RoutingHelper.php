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
        return Route::is(['admin.posts.drafts']);
    }

    public static function isPodcastRoute() {
        return self::isPodcastEdit() || self::isPodcastCreate() || self::isPodcastIndex() || self::isPodcastDraft();
    }

    public static function isPodcastCreate() {
        return Route::is(['admin.podcasts.create']);
    }

    public static function isPodcastIndex() {
        return Route::is(['admin.podcasts.index']);
    }

    public static function isPodcastEdit() {
        return Route::is(['admin.podcasts.edit']);
    }

    public static function isPodcastDraft() {
        return Route::is(['admin.podcasts.drafts']);
    }

    public static function isCommentsIndex() {
        return Route::is('admin.posts.comments');
    }
}
