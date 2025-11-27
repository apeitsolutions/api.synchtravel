<?php

	//check employee image exist in server or not
	if (!function_exists('employee_image_exists')) {
		function employee_image_exists($file){

			if (file_exists(public_path('uploads/employee_imgs/'.$file))) {

        		return true;
    		}else{

    			return false;
    		}
		}
	}
?>