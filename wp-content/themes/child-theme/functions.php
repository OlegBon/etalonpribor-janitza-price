<?php
/**
 * Підключення зовнішнього JS-файлу з даними про пристрої (deviceData)
 * Файл розміщений у /wp-content/uploads/_data-pribor.js
 * Використовується для відображення інформації про пристрої на сторінках продуктів
 */
function enqueue_device_scripts() {
    wp_enqueue_script(
        'device-data',
        content_url('/uploads/_data-pribor.js'),
        [],
        null,
        true
    );

    wp_enqueue_script(
        'device-init',
        get_stylesheet_directory_uri() . '/js/device-init.js',
        ['device-data'],
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_device_scripts');