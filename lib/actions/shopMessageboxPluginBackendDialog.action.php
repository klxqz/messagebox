<?php

class shopMessageboxPluginBackendDialogAction extends waViewAction {

    public function execute() {
        $messagebox_id = waRequest::get('messagebox_id');
        if ($messagebox_id) {
            $model = new shopMessageboxPluginModel();
            $messagebox = $model->getById($messagebox_id);
            $this->view->assign('messagebox', $messagebox);
        }
    }

}
