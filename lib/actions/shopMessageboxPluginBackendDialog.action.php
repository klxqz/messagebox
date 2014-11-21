<?php

class shopMessageboxPluginBackendDialogAction extends waViewAction {

    public function execute() {

        $params = array(
            'padding' => array('name' => 'padding', 'title' => 'Отступы внутри окна', 'default' => 15),
            'margin' => array('name' => 'margin', 'title' => 'Отступы', 'default' => 20),
            'width' => array('name' => 'width', 'title' => 'Ширина', 'default' => 800),
            'height' => array('name' => 'height', 'title' => 'Высота', 'default' => 600),
            'minWidth' => array('name' => 'minWidth', 'title' => 'Минимальная ширина', 'default' => 100),
            'minHeight' => array('name' => 'minHeight', 'title' => 'Минимальная высота', 'default' => 100),
            'maxWidth' => array('name' => 'maxWidth', 'title' => 'Максимальная ширина', 'default' => 9999),
            'maxHeight' => array('name' => 'maxHeight', 'title' => 'Максимальная высота', 'default' => 9999),
            'autoSize' => array('name' => 'autoSize', 'title' => 'Авторазмер', 'default' => true),
            'autoResize' => array('name' => 'autoResize', 'title' => 'Авто изменение размера', 'default' => true),
        );
        $messagebox_id = waRequest::get('messagebox_id');
        if ($messagebox_id) {
            $model = new shopMessageboxPluginModel();
            $messagebox = $model->getById($messagebox_id);
            $messagebox['params'] = json_decode($messagebox['params'], true);
            if (is_array($messagebox['params'])) {
                foreach ($messagebox['params'] as $name => $value) {
                    if (isset($params[$name])) {
                        $params[$name]['value'] = $value;
                    }
                }
            }
            $this->view->assign('messagebox', $messagebox);
        }
        $this->view->assign('params', $params);
    }

}
