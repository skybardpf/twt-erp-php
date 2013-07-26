<?php
    return CMap::mergeArray(
        // наследуемся от main.php
        require(dirname(__FILE__).'/main.php'),

        array(
            'components' => array(
                'cache' => array('class'=>'system.caching.CFileCache'),
            )
        )
    );