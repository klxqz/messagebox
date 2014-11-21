<?php

class shopMessageboxPluginSettingsAction extends waViewAction {

    protected $plugin_id = array('shop', 'messagebox');

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $model = new shopMessageboxPluginModel();
        $rows = $model->getAll();
        foreach ($rows as &$row) {
            if ($row['type'] == 'link') {
                $row['helper'] = '{shopMessageboxPlugin::display(' . $row['id'] . ')}';
            } else {
                $row['helper'] = 'Работает автоматически при переходе на страницу: ' . $row['url'];
            }
        }
        unset($row);
        $this->view->assign('settings', $settings);
        $this->view->assign('rows', $rows);
        $this->view->assign('lang', substr(wa()->getLocale(), 0, 2));
    }

}
