<?php

class shopMessageboxPluginFrontendAction extends waViewAction {

    public function execute() {
        $id = waRequest::param('id');
        if($id) {
            $model = new shopMessageboxPluginModel();
            $messagebox = $model->getById($id);
            $this->view->assign('messagebox', $messagebox);
        }
    }

}
