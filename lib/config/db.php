<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'shop_messagebox' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'description' => array('text'),
        ':keys' => array(
            'PRIMARY' => array('id'),
        ),
    )
);
