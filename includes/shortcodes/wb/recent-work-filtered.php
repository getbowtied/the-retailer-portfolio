<?php

// [portfolio]

vc_map(array(
   "name"			=> "Portfolio",
   "category" 		=> "The Retailer",
   "description"	=> "Place Portfolio",
   "base"			=> "recent_work_filtered",
   "class"			=> "",
   
   "params" 	=> array(
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Items per Row (in a Boxed Row)",
			"param_name"	=> "items_per_row",
			"value"			=> array(
				"2"	=> "2",
				"3"	=> "3",
				"4"	=> "4"
			),
			"std"			=> "4",
		),
   )
   
));