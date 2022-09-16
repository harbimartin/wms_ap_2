<?php

use App\Http\Helper\HTML;

HTML::macro('jrsTableDetail', function($array)
{
	$list = '<div class="bs-example"><ul class="list-group">';
	foreach($array as $a){
		$list .= '<li class="list-group-item"><b>'.$a[0].': </b>'.$a[1].'</li>';
	}
	$list .= '</ul></div>';

    return $list;
});

HTML::macro('jrsBtn', function($array)
{
	(isset($array[5]))
		? $icon = $array[5]
		: $icon = 'plus';

	$class = 'btn btn-'.$array[2].' btn-sm '.$array[3];

	($array[4] == TRUE)
		? $onClick = 'return doconfirm();'
		: $onClick = NULL;

	$link = '<a href="'.url($array[0]).'" class="'.$class.'" onClick="'.$onClick.'"><i class="fa fa-'.$icon.'"></i> '.$array[1].'</a>';
    return $link;
});

HTML::macro('jrsTableColumn',function($array){
	$column = null;

	foreach($array as $a) $column .= '<td align="center">'.$a.'</td>';

	return $column;
});

HTML::macro('jrsTableHead',function($array){
	foreach($array as $a){
		$head[]['name'] = $a;
	}
	return $head;
});
