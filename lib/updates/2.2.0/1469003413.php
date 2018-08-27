<?php

$model = new waModel();
try {
    $sql = "ALTER TABLE `shop_messagebox` CHANGE `type` `type` ENUM( 'link', 'url', 'product', 'category' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'link'";
    $model->query($sql);
} catch (waDbException $ex) {
    
}

try {
    $sql = 'ALTER TABLE `shop_messagebox` ADD `multiplicity` INT NOT NULL AFTER `first_visit`';
    $model->query($sql);
} catch (waDbException $ex) {
    
}

