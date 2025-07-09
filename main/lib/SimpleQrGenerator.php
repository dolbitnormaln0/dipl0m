<?php

namespace lib;

class SimpleQrGenerator {
    public static function generate(string $text, int $size = 200): string {
        $text = htmlspecialchars($text, ENT_QUOTES);
        $size = max(100, min(1000, $size));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="{$size}" height="{$size}" viewBox="0 0 21 21">
    <rect width="21" height="21" fill="#FFFFFF"/>
    <path fill="#000000" d="M0 0h1v1H0zM1 0h1v1H1zM2 0h1v1H2zM3 0h1v1H3zM4 0h1v1H4zM5 0h1v1H5zM6 0h1v1H6zM7 0h1v1H7zM8 0h1v1H8zM9 0h1v1H9zM10 0h1v1H10zM11 0h1v1H11zM12 0h1v1H12zM13 0h1v1H13zM14 0h1v1H14zM15 0h1v1H15zM16 0h1v1H16zM17 0h1v1H17zM18 0h1v1H18zM19 0h1v1H19zM20 0h1v1H20z
    <!-- Это упрощенный пример - в реальности нужно генерировать правильные QR-паттерны -->
    "/>
</svg>
SVG;

        return $svg;
    }
}