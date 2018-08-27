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

            $messagebox['params'] = json_encode($messagebox['params']);
            $messagebox['categories'] = json_encode($messagebox['categories']);
            $model = new shopMessageboxPluginModel();

            if (isset($messagebox['id']) && $messagebox['id']) {
                $model->updateById($messagebox['id'], $messagebox);
            } else {
                $lastInsertId = $model->insert($messagebox);
                $messagebox['id'] = $lastInsertId;
            }
            if ($messagebox['type'] == 'link') {
                $messagebox['helper'] = '{shopMessageboxPlugin::display(' . $messagebox['id'] . ')}';
            } elseif ($messagebox['type'] == 'url') {
                $messagebox['helper'] = 'Работает автоматически при переходе на страницу: ' . $messagebox['url'];
            } elseif ($messagebox['type'] == 'product') {
                $messagebox['helper'] = 'Работает автоматически при переходе на страницу товара';
            } elseif ($messagebox['type'] == 'category') {
                $messagebox['helper'] = 'Работает автоматически при переходе на страницу категории';
            }

            $messagebox['name'] = htmlspecialchars($messagebox['name'], ENT_COMPAT, 'UTF-8');

            $this->response['messagebox'] = $messagebox;
            $this->response['message'] = 'Сохранено';
        } catch (Exception $ex) {
            $this->errors = $ex->getMessage();
        }
    }

}
