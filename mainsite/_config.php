<?php

global $project;
$project = 'mainsite';

global $database;
$database = SS_DATABASE_NAME;

Email::mailer()->setMessageEncoding('base64');

// Use _ss_environment.php file for configuration
require_once("conf/ConfigureFromEnv.php");

if (extension_loaded('imagick')) {
    ImagickBackend::set_default_quality(90);
    Image::set_backend("ImagickBackend");
} else {
    GD::set_default_quality(90);
    Image::set_backend("OptimisedGDBackend");
}


Requirements::set_write_js_to_body(false);

SS_Cache::set_cache_lifetime('Property', 86400); // 1 day

if (Director::isLive()) {
    Director::forceSSL();
    Director::forceWWW();
    SS_Log::add_writer(new SS_LogEmailWriter('leochenftw@gmail.com'), SS_Log::ERR);
}

if(class_exists('Memcache')){
    SS_Cache::add_backend(
        'primary_memcached',
        'Memcached',
        array(
            'host' => 'localhost',
            'port' => 11211,
            'persistent' => true,
            'weight' => 1,
            'timeout' => 1,
            'retry_interval' => 15,
            'status' => true,
            'failure_callback' => ''
        )
    );
    SS_Cache::pick_backend('primary_memcached', 'any', 10);
}
