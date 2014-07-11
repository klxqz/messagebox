<?php

class shopMessageboxPlugin extends shopPlugin {

    public function frontendHead() {
        $plugin_id = array('shop', 'messagebox');
        $app_settings_model = new waAppSettingsModel();
        if ($app_settings_model->get($plugin_id, 'status')) {
            $html = '';
            if ($app_settings_model->get($plugin_id, 'include_fancybox')) {
                waSystem::getInstance()->getResponse()->addJs('plugins/messagebox/js/fancybox/jquery.fancybox.pack.js', 'shop');
                waSystem::getInstance()->getResponse()->addCss('plugins/messagebox/js/fancybox/jquery.fancybox.css', 'shop');

                $html .= <<<HTML
        <script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();
		});
	</script>
HTML;
            }
            return $html;
        }
    }

}
