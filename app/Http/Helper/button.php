<?php

use App\Http\Helper\HTML;

HTML::macro('PlDoneBtn', function($param)
{
	($param[0] == "Order Sent")
		? $DoneBtn = HTML::jrsBtn(['outbound/done-pl/'.$param[1],'Done','success','',TRUE])
		: $DoneBtn = NULL;

	return $DoneBtn;
});
