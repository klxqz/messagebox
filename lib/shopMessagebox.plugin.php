<?php

class shopMessageboxPlugin extends shopPlugin
{

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

    public static function display($messagebox_id)
    {
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

    private function makeUrlPattern($url)
    {
        $url = preg_replace('/https?:\/\//', '', $url);
        $url = str_replace("*", ".*", $url);
        $url = str_replace("/", "\/", $url);
        $url = str_replace("?", "\?", $url);
        return $url;
    }

    public function getMessageboxByUrl($url)
    {
        $model = new shopMessageboxPluginModel();
        $messageboxes = $model->select('*')->where("`url` != ''")->fetchAll();
        foreach ($messageboxes as $messagebox) {
            $categories = json_decode($messagebox['categories'], true);
            if (array_intersect($categories, self::getUserCategoryId())) {
                $pattern = $this->makeUrlPattern($messagebox['url']);
                if (preg_match('/' . $pattern . '/', $url)) {
                    return $messagebox;
                }
            }
        }
        return false;
    }

    public function frontendHead()
    {
        if ($this->getSettings('status')) {
            $url = wa()->getConfig()->getHostUrl() . wa()->getConfig()->getRequestUrl(false, true);
            $messagebox = $this->getMessageboxByUrl($url);
            if (!$messagebox) {
                $url = wa()->getConfig()->getHostUrl() . wa()->getConfig()->getRequestUrl(false, true) . '?' . waRequest::server('QUERY_STRING');
                $messagebox = $this->getMessageboxByUrl($url);
            }
            $html = $this->messageboxDisplay($messagebox);
            return $html;
        }
    }

    public function frontendProduct($product)
    {
        if ($this->getSettings('status')) {
            $html = '';
            $model = new shopMessageboxPluginModel();
            $messageboxs = $model->getByField('type', 'product', true);
            foreach ($messageboxs as $messagebox) {
                $categories = json_decode($messagebox['categories'], true);
                if (array_intersect($categories, self::getUserCategoryId())) {
                    $html = $this->messageboxDisplay($messagebox);
                    break;
                }
            }

            return array('cart' => $html);
        }
    }

    public function frontendCategory($category)
    {
        if ($this->getSettings('status')) {
            $html = '';
            $model = new shopMessageboxPluginModel();
            $messageboxs = $model->getByField('type', 'category', true);
            foreach ($messageboxs as $messagebox) {
                $categories = json_decode($messagebox['categories'], true);
                if (array_intersect($categories, self::getUserCategoryId())) {
                    $html = $this->messageboxDisplay($messagebox);
                    break;
                }
            }

            return $html;
        }
    }

    public static function getUserCategoryId($contact_id = null)
    {
        if ($contact_id === null) {
            $contact_id = wa()->getUser()->getId();
        }
        $model = new waModel();
        $sql = "SELECT * FROM `wa_contact_categories` WHERE `contact_id` = '" . $model->escape($contact_id) . "'";
        $categories = $model->query($sql)->fetchAll();
        $category_ids = array();
        $category_ids[] = 0;
        foreach ($categories as $category) {
            $category_ids[] = $category['category_id'];
        }
        return $category_ids;
    }

    private function messageboxDisplay($messagebox)
    {
        if (!$messagebox) {
            return false;
        }


        $hash = md5(serialize($messagebox));

        if ($messagebox['multiplicity']) {
            wa()->getResponse()->setCookie('messagebox_showcount_' . $hash, waRequest::cookie('messagebox_showcount_' . $hash) + 1, time() + 30 * 86400, null, '', false, true);
        }

        if (
            ($messagebox['first_visit'] == 1 && waRequest::cookie('messagebox_' . $hash)) ||
            ($messagebox['multiplicity'] && waRequest::cookie('messagebox_showcount_' . $hash) % $messagebox['multiplicity'] != 1)
        ) {
            return;
        }

        if ($this->getSettings('include_fancybox')) {
            waSystem::getInstance()->getResponse()->addJs('plugins/messagebox/js/fancybox/jquery.fancybox.pack.js', 'shop');
            waSystem::getInstance()->getResponse()->addCss('plugins/messagebox/js/fancybox/jquery.fancybox.css', 'shop');
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
