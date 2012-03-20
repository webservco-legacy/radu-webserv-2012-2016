#!/bin/bash

# $Id$

function viblog_viblog_files_cleanup_input
{
	p_header "Convert placeholder Post ID"
	
	echo -n "Please enter Post ID"
	if [ "${POST_ID_NEXT}" != "" ]; then
		echo -n " [next available: ${POST_ID_NEXT}] "
	fi 
	echo -n ": "
	read POST_ID
	if [ "$POST_ID" != "" ]; then
		
		echo -n "Renaming assets directory... "
		mv "${WORKPATH}${DIR_OUT}/${POST_ID_PH}" "${WORKPATH}${DIR_OUT}/${POST_ID}"
		echo "Done"
		echo ""
		
		echo -n "Renaming SQL file... "
		mv "${WORKPATH}${DIR_OUT}/${POST_ID_PH}.sql" "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
		echo "Done"
		echo ""
		
		echo -n "Processing SQL file... "
		sed -i "s/${POST_ID_PH}/${POST_ID}/g" "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
		echo "Done"
		echo ""
		
	else
		echo "ERROR: No ID specified"
	fi
	
	p_footer

}

function viblog_files_cleanup_input
{
	p_header "Cleanup input directory"
	
	echo -n "Removing files.. "
	
	rm -rf "${WORKPATH}${DIR_IN}/"*
	
	echo "Done"
	
	p_footer
	
}

function viblog_files_cleanup_output
{
	p_header "Cleanup output directory"
	
	echo -n "Removing files.. "
	
	rm -rf "${WORKPATH}${DIR_OUT}/"*
	
	echo "Done"
	
	p_footer
}

function viblog_files_cleanup_temp
{
	p_header "Cleanup temp directory"
	
	echo -n "Removing files.. "
	
	rm -rf "${WORKPATH}${DIR_TMP}/"*
	
	echo "Done"
	
	p_footer
}