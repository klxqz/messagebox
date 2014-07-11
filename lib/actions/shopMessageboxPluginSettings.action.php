<?php

class shopMessageboxPluginSettingsAction extends waViewAction {

    protected $plugin_id = array('shop', 'messagebox');

    public function execute() {
        $app_settings_model = new waAppSettingsModel();
        $settings = $app_settings_model->get($this->plugin_id);

        $model = new shopMessageboxPluginModel();
        $rows = $model->getAll();
        foreach ($rows as &$row) {
            $row['url'] = '<a class="fancybox fancybox.ajax" href="' .
                    wa()->getRouteUrl('/frontend', array('plugin' => 'messagebox', 'id' => $row['id'])) . '">' .
                    htmlspecialchars($row['name'], ENT_COMPAT, 'UTF-8', true) . '</a>';
        }
        $this->view->assign('settings', $settings);
        $this->view->assign('rows', $rows);
        $this->view->assign('lang', substr(wa()->getLocale(), 0, 2));
    }

}
