<?php

namespace traits_;
trait CacheTrait
{
    protected function cacheGet(string $key, callable $callback, int $ttl = 3600)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['cache'][$key]) && $_SESSION['cache'][$key]['expires'] > time()) {
            echo 'item loaded from cache';
            return $_SESSION['cache'][$key]['data'];
        }
        $data = $callback();
        $_SESSION['cache'][$key] = [
            'data' => $data,
            'expires' => time() + $ttl
        ];

        return $data;
    }

    protected function cacheDelete(string $key)
    {
        if (isset($_SESSION['cache'][$key])) {
            unset($_SESSION['cache'][$key]);
        }
    }
}