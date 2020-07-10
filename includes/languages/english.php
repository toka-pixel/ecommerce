<?php

 function lang($phrase){

    static $lang=array(

       
        'HOME_ADMIN' => 'admin area',
        'CATEGORIES'=>'categories',
        'ITEMS'=>'items',
         'MEMBERS'=>'members',
         'COMMENTS'=>'comments',
        'STATISTICS'=>'statistics',
        'LOGOS'=>'logos'
    );
    return $lang[$phrase];
 }
 ?>