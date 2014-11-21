<?php

$model = new waModel();
try {
    $sql = 'SELECT `image` FROM `shop_messagebox` WHERE 0';
    $model->query($sql);
} catch (waDbException $ex) {
    $sql = "ALTER TABLE `shop_messagebox` ADD `type` ENUM( 'link', 'url' ) NOT NULL ,
    ADD `url` VARCHAR( 255 ) NOT NULL ,
    ADD `first_visit` TINYINT( 1 ) NOT NULL ,
    ADD `params` TEXT NOT NULL ";
    $model->query($sql);
}