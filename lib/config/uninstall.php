<?php

$model = new waModel();
try {
    $model->exec("DROP TABLE `shop_messagebox`");
} catch (waDbException $e) {
    
}


