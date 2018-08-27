<?php

return array(
    'name' => 'Всплывающие окна',
    'description' => 'Произвольное количество всплывающих окон с сообщениями',
    'vendor' => '985310',
    'version' => '2.3.0',
    'img' => 'img/messagebox.png',
    'shop_settings' => true,
    'frontend' => true,
    'handlers' => array(
        'frontend_head' => 'frontendHead',
        'frontend_product' => 'frontendProduct',
        'frontend_category' => 'frontendCategory',
    ),
);
//EOF
