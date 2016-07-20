<?php

class shopMessageboxPlugin extends shopPlugin {

    protected static $plugin_id = array('shop', 'messagebox');
    protected static $params = array(
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

    public static function display($messagebox_id) {
        $model = new shopMessageboxPluginModel();
        $app_settings_model = new waAppSettingsModel();
        if ($app_settings_model->get(self::$plugin_id, 'status') && ($messagebox = $model->getById($messagebox_id))) {
            $view = wa()->getView();
            $params = self::$params;

            $messagebox['params'] = json_decode($messagebox['params'], true);
            if (is_array($messagebox['params'])) {
                foreach ($messagebox['params'] as $name => $value) {
                    if (isset($params[$name])) {
                        $params[$name]['value'] = $value;
                    }
                }
            }
            $view->assign('messagebox', $messagebox);
            $view->assign('params', $params);

            $template_path = wa()->getAppPath('plugins/messagebox/templates/actions/frontend/Messagebox.html', 'shop');
            $html = $view->fetch($template_path);
            return $html;
        }
    }

    private function makeUrlPattern($url) {
        $url = preg_replace('/https?:\/\//', '', $url);
        $url = str_replace("*", ".*", $url);
        $url = str_replace("/", "\/", $url);
        return $url;
    }

    public function getMessageboxByUrl($url) {
        $model = new shopMessageboxPluginModel();
        $messageboxes = $model->select('*')->where("`url` != ''")->fetchAll();
        foreach ($messageboxes as $messagebox) {
            $pattern = $this->makeUrlPattern($messagebox['url']);
            if (preg_match('/' . $pattern . '/', $url)) {
                return $messagebox;
            }
        }
        return false;
    }

    public function frontendHead() {
        $app_settings_model = new waAppSettingsModel();
        if ($app_settings_model->get(self::$plugin_id, 'status')) {
            if ($app_settings_model->get(self::$plugin_id, 'include_fancybox')) {
                waSystem::getInstance()->getResponse()->addJs('plugins/messagebox/js/fancybox/jquery.fancybox.pack.js', 'shop');
                waSystem::getInstance()->getResponse()->addCss('plugins/messagebox/js/fancybox/jquery.fancybox.css', 'shop');
            }

            $url = wa()->getConfig()->getHostUrl().wa()->getConfig()->getRequestUrl(false, true);
            $messagebox = $this->getMessageboxByUrl($url);

            if ($messagebox) {
                $hash = sha1($messagebox['id'] . $messagebox['url'] . $messagebox['type']);
                if ($messagebox['first_visit'] == 1 && waRequest::cookie('messagebox_' . $hash)) {
                    return;
                }
                $view = wa()->getView();
                $params = self::$params;

                $messagebox['params'] = json_decode($messagebox['params'], true);
                if (is_array($messagebox['params'])) {
                    foreach ($messagebox['params'] as $name => $value) {
                        if (isset($params[$name])) {
                            $params[$name]['value'] = $value;
                        }
                    }
                }
                $view->assign('messagebox', $messagebox);
                $view->assign('params', $params);

                $template_path = wa()->getAppPath('plugins/messagebox/templates/actions/frontend/Messagebox.html', 'shop');
                $html = $view->fetch($template_path);

                if ($messagebox['first_visit']) {
                    wa()->getResponse()->setCookie('messagebox_' . $hash, 1, time() + 30 * 86400, null, '', false, true);
                }
                return $html;
            }
        }
    }

}
