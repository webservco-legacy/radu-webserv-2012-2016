#!/bin/bash

# $Id$

function viblog_server_upload
{
	p_header "Fiel upload"
	
	echo -n "Please enter Post ID: "
	read POST_ID
	if [ "$POST_ID" != "" ]; then
		
		echo "Starting file upload"
		
		ncftpput -R -v -u "${FTP_USER}" -p "${FTP_PASS}" "${FTP_HOST}" \
		"${FTP_PATH_ASSETS}" \
		"${WORKPATH}${DIR_OUT}/${POST_ID}"
		
	else
		echo "ERROR: No ID specified"
	fi
	
	p_footer
}

function viblog_server_database_insert
{
	p_header "Database insert"
	
	echo -n "Please enter Post ID: "
	read POST_ID
	if [ "$POST_ID" != "" ]; then
		
		echo -n "Execution query... "
		
		mysql --host="${DB_HOST}" --user="${DB_USER}" --password="${DB_PASS}" \
		--max_allowed_packet=16M --default-character-set=utf8 \
		"${DB_NAME}" < "${WORKPATH}${DIR_OUT}/${POST_ID}.sql" \
		>> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
		echo "Done"
		echo ""
		
	else
		echo "ERROR: No ID specified"
	fi
	
	p_footer
}