#!/bin/bash

# $Id$

# viblog_get_media_resize_height MEDIA_WIDTH MEDIA_HEIGHT MEDIA_RESIZE_WIDTH
function viblog_get_media_resize_height
{
	MEDIA_WIDTH=$1
	MEDIA_HEIGHT=$2
	MEDIA_RESIZE_WIDTH=$3
	awk "BEGIN { rounded = sprintf(\"%.0f\", ${MEDIA_RESIZE_WIDTH}/(${MEDIA_WIDTH}/${MEDIA_HEIGHT})); print rounded }"
}

# viblog_viblog_media_add_description_file_func FILE_NAME DESCRIPTION
function viblog_viblog_media_add_description_file_func
{
	FILE_NAME=$1
	DESCRIPTION=$2
	
	echo "${DESCRIPTION}" > "${WORKPATH}${DIR_IN}/${FILE_NAME}.${META_EXT}"
}

function viblog_media_add_description_file
{
	p_header "Add description [file] to media"
	
	echo -n "File name: "
	read FILE_NAME
	
	if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
		
		echo -n "Opening preview... "
		xdg-open "${WORKPATH}${DIR_IN}/${FILE_NAME}" > /dev/null 2>&1
		echo "Done"
		echo ""
	
		echo -n "Please enter description for \"${FILE_NAME}\": "
		read DESCRIPTION
		
		echo -n "Writing description to file... "
		viblog_viblog_media_add_description_file_func "${FILE_NAME}" "${DESCRIPTION}"
		echo "Done"
		echo ""
			
	else
		echo "ERROR: File not found"
	fi
	
	p_footer
}

function viblog_media_add_description_file_all
{
	p_header "Add description [file] to media [all]"
	
	echo -n "File extension to use: [leave blank to use default image extension \"${IMG_EXT_IN}\"]"
	read FILE_EXT
	
	if [ "${FILE_EXT}" = "" ]; then
		FILE_EXT=${IMG_EXT_IN}
	fi
	
	echo "Using file extension \"${FILE_EXT}\""
	
	echo ""
	
	TOTAL_FILES=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | wc -l`
	echo -n "Total files files found: ${TOTAL_FILES}"
	
	echo ""
	
	if [ "${TOTAL_FILES}" != "0" ]; then
	
		echo "Starting file iteration"
	
		echo ""
		
		find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | while read FILE;
		do
			
			FILE_NAME=`basename "${FILE}"`
			
			echo -n "Opening preview... "
			xdg-open "${WORKPATH}${DIR_IN}/${FILE_NAME}" > /dev/null 2>&1
			echo "Done"
			echo ""
			
			echo -n "Please enter description for \"${FILE_NAME}\": "
			read DESCRIPTION < /dev/tty
		
			echo -n "Writing description to file... "
			viblog_viblog_media_add_description_file_func "${FILE_NAME}" "${DESCRIPTION}"
			echo "Done"
			echo ""
		
		done
	
	else
		echo "No files to process"
	fi
	
	p_footer
}

# viblog_media_get_description_file_func FILE_PATH
function viblog_media_get_description_file_func
{
	FILE_PATH=$1
	
	if [ -f "${FILE_PATH}.${META_EXT}" ]; then
		cat "${FILE_PATH}.${META_EXT}"
	fi
}