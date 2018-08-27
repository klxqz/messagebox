<?php

class shopMessageboxPluginSettingsAction extends waViewAction {

    public function execute() {
        $model = new shopMessageboxPluginModel();
        $rows = $model->getAll();
        foreach ($rows as &$row) {
            if ($row['type'] == 'link') {
                $row['helper'] = '{shopMessageboxPlugin::display(' . $row['id'] . ')}';
            } elseif ($row['type'] == 'url') {
                $row['helper'] = 'Работает автоматически при переходе на страницу: ' . $row['url'];
            } elseif ($row['type'] == 'product') {
                $row['helper'] = 'Работает автоматически при переходе на страницу товара';
            } elseif ($row['type'] == 'category') {
                $row['helper'] = 'Работает автоматически при переходе на страницу категории';
            }
        }
        unset($row);

        $this->view->assign(array(
            'plugin' => wa()->getPlugin('messagebox'),
            'rows' => $rows,
            'lang' => substr(wa()->getLocale(), 0, 2),
        ));
    }

}
