<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
class shopMessageboxPluginBackendSaveController extends waJsonController {

    public function execute() {
        try {
            $messagebox = waRequest::post('messagebox');
            if (!$messagebox || !is_array($messagebox)) {
                throw new Exception('Ошибка передачи данных');
            }
            $model = new shopMessageboxPluginModel();

            if (isset($messagebox['id']) && $messagebox['id']) {
                $model->updateById($messagebox['id'], $messagebox);
            } else {
                $lastInsertId = $model->insert($messagebox);
                $messagebox['id'] = $lastInsertId;
            }
            $url = wa()->getRouteUrl('/frontend', array('plugin' => 'messagebox', 'id' => $messagebox['id']));
            $link = '<a class="fancybox fancybox.ajax" href="' . $url . '">' .
                    htmlspecialchars($messagebox['name'], ENT_COMPAT, 'UTF-8') . '</a>';
            $messagebox['url'] = htmlspecialchars($link, ENT_COMPAT, 'UTF-8');
            $messagebox['name'] = htmlspecialchars($messagebox['name'], ENT_COMPAT, 'UTF-8');

            $this->response['messagebox'] = $messagebox;
            $this->response['message'] = 'Сохранено';
        } catch (Exception $ex) {
            $this->errors = $ex->getMessage();
        }
    }

}
