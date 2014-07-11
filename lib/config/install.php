<?php

$plugin_id = array('shop', 'messagebox');
$app_settings_model = new waAppSettingsModel();
$app_settings_model->set($plugin_id, 'status', '1');
$app_settings_model->set($plugin_id, 'include_fancybox', '1');
