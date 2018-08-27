<?php

$model = new waModel();
try {
    $sql = "ALTER TABLE `shop_messagebox` ADD `categories` TEXT NOT NULL";
    $model->query($sql);
} catch (waDbException $ex) {
    
}

$files = array(
    'plugins/messagebox/lib/actions/shopMessageboxPluginBackendDelete.controller.php',
    'plugins/messagebox/lib/actions/shopMessageboxPluginBackendDialog.action.php',
    'plugins/messagebox/lib/actions/shopMessageboxPluginBackendSave.controller.php',
    'plugins/messagebox/lib/actions/shopMessageboxPluginFrontend.action.php',
    'plugins/messagebox/lib/actions/shopMessageboxPluginSettings.action.php',
    'plugins/messagebox/lib/config/uninstall.php',
);

foreach ($files as $file) {
    try {
        waFiles::delete(wa()->getAppPath($file, 'shop'), true);
    } catch (Exception $e) {
        
    }
}