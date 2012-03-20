#!/bin/bash

# $Id$

function page_viblog
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: VIBlog" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"p" "Post >>" \
		"i" "Image >>" \
		"v" "Video >>" \
		"s" "Server >>" \
		"f" "Files >>" \
		"g" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done;
}

function page_viblog_post
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: Post" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"pi" "Get next Post ID" \
		"pq" "Prepare Post" \
		"pa" "Activate Post" \
		"pd" "Deactivate Post" \
		"b" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done
}

function page_viblog_image
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: Images" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"im" "Add meta description [one]" \
		"ima" "Add meta description [all]" \
		"id" "Add file description [one]" \
		"ida" "Add file description [all]" \
		"iv" "View description [all]" \
		"ip" "Process" \
		"b" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done
}

function page_viblog_video
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: Video operations" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"vc" "Cut" \
		"vf" "Encode + fade [one]" \
		"vfa" "Encode + fade [all]" \
		"ve" "Encode [one]" \
		"vea" "Encode [all]" \
		"vm" "Add meta description [one]" \
		"vma" "Add meta description [all]" \
		"vd" "Add file description [one]" \
		"vda" "Add file description [all]" \
		"vv" "View description [all]" \
		"vp" "Process" \
		"b" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done
}

function page_viblog_server
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: Server" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"su" "Upload files" \
		"sd" "Database insert" \
		"b" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done
}

function page_viblog_files
{
	while [ 0 ]; do
		dialog --title "${P_NAME} :: Files" --menu \
		"Hello $P_USER!" \
		20 60 13 \
		"fc" "Convert placeholder Post ID" \
		"fi" "Cleanup input directory" \
		"fo" "Cleanup output directory" \
		"ft" "Cleanup temp directory" \
		"b" "Back" \
		"x" "Exit" \
		2> "/tmp/${P_NAME}"
		if [ $? = 1 ]; then
			show_gui
		fi
		handle_menu
	done
}