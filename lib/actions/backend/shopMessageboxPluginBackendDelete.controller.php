<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopMessageboxPluginBackendDeleteController extends waJsonController {

    public function execute() {
        $messagebox_id = waRequest::post('messagebox_id');
        if ($messagebox_id && intval($messagebox_id)) {
            $model = new shopMessageboxPluginModel();
            $model->deleteById($messagebox_id);
        }
    }

}
