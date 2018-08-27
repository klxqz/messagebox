<?php

/**
 * @author wa-plugins.ru <support@wa-plugins.ru>
 * @link http://wa-plugins.ru/
 */
return array(
    'shop_messagebox' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'description' => array('text', 'null' => 0),
        'type' => array('enum', "'link','url','product','category'", 'null' => 0, 'default' => 'link'),
        'url' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'first_visit' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        'multiplicity' => array('int', 11, 'null' => 0),
        'categories' => array('text', 'null' => 0),
        'params' => array('text', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('id'),
        ),
    )
);
