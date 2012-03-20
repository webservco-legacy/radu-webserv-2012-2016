#!/bin/bash

# $Id$

function handle_menu
{
	R=`cat "/tmp/${P_NAME}"`
	
	if [ "$R" == "g" ]; then
		show_gui
	
	# VIBLOG
	elif [ "$R" == "b" ]; then
		page_viblog
		
	# FILE
	elif [ "$R" == "p" ]; then
		page_viblog_post
		
	elif [ "$R" == "pi" ]; then
		viblog_post_get_next_id
		
	elif [ "$R" == "pq" ]; then
		viblog_post_prepare
		
	elif [ "$R" == "pa" ]; then
		viblog_post_activate
		
	elif [ "$R" == "pd" ]; then
		viblog_post_deactivate
		
	# IMAGE
	elif [ "$R" == "i" ]; then
		page_viblog_image
		
	elif [ "$R" == "im" ]; then
		viblog_image_add_description_meta
	
	elif [ "$R" == "ima" ]; then
		viblog_image_add_description_meta_all
		
	elif [ "$R" == "id" ]; then
		viblog_media_add_description_file
	
	elif [ "$R" == "ida" ]; then
		viblog_media_add_description_file_all
	
	elif [ "$R" == "iv" ]; then
		viblog_image_get_description_all
			
	elif [ "$R" == "ip" ]; then
		viblog_image_process
		
	# VIDEO
	elif [ "$R" == "v" ]; then
		page_viblog_video

	elif [ "$R" == "vc" ]; then
		viblog_video_cut
		
	elif [ "$R" == "vf" ]; then
		viblog_video_fade
	
	elif [ "$R" == "vfa" ]; then
		viblog_video_fade_all
		
	elif [ "$R" == "ve" ]; then
		viblog_video_encode
	
	elif [ "$R" == "vea" ]; then
		viblog_video_encode_all
		
	elif [ "$R" == "vm" ]; then
		viblog_video_add_description_meta
	
	elif [ "$R" == "vma" ]; then
		viblog_video_add_description_meta_all
		
	elif [ "$R" == "vd" ]; then
		viblog_media_add_description_file
	
	elif [ "$R" == "vda" ]; then
		viblog_media_add_description_file_all
		
	elif [ "$R" == "vv" ]; then
		video_get_description_all

	elif [ "$R" == "vp" ]; then
		viblog_video_process
		
	# SERVER
	elif [ "$R" == "s" ]; then
		page_viblog_server
	
	elif [ "$R" == "su" ]; then
		viblog_server_upload
	
	elif [ "$R" == "sd" ]; then
		viblog_server_database_insert
		
	# FILES
	elif [ "$R" == "f" ]; then
		page_viblog_files
	
	elif [ "$R" == "fc" ]; then
		viblog_viblog_files_cleanup_input
	
	elif [ "$R" == "fi" ]; then
		viblog_files_cleanup_input
		
	elif [ "$R" == "fo" ]; then
		viblog_files_cleanup_output
		
	elif [ "$R" == "ft" ]; then
		viblog_files_cleanup_temp
			
	# GENERAL	
	elif [ "$R" == "h" ]; then
		page_help
	
	elif [ "$R" == "a" ]; then
		page_about	
		
	elif [ "$R" == "x" ]; then
		exit_ok
	fi
}