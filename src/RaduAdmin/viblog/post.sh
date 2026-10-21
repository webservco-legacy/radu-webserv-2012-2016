#!/bin/bash

# $Id$

function viblog_post_get_next_id
{
    p_header "Get next Post ID"
    
    echo -n "Execution query... "
    DBQUERY=$(mysql --host="${DB_HOST}" --user="${DB_USER}" --password="${DB_PASS}" --database="${DB_NAME}" \
    --execute="SELECT (COALESCE(MAX(post_id), 0) + 1) AS next_post_id FROM r_viblog" -s)
    DBRESULT=( $( for i in $DBQUERY; do echo $i ; done ) )
    echo "Done"
    echo ""
    
    echo -n "Next Post ID is: "
    POST_ID_NEXT=${DBRESULT[@]}
    echo ${POST_ID_NEXT}
    echo ""
    echo "The Next Post ID is kept in memory and can be used until the program is closed"
    
    p_footer
}

function viblog_post_prepare
{
    p_header "Prepare post"
    
    echo -n "Please enter Post ID"
    if [ "${POST_ID_NEXT}" != "" ]; then
        echo -n " [next available: ${POST_ID_NEXT}] "
    fi 
    echo -n ": "
    read POST_ID
    if [ "$POST_ID" != "" ]; then
        
        echo -n "Please enter post title [optional]: "
        read POST_TITLE
        
        echo -n "Please enter post description [optional]: "
        read POST_DESCRIPTION
        
        TODAY=`date +"%Y-%m-%d"`
        
        echo -n "Please enter post start date [leave empty to use today: \"${TODAY}\"]: "
        read POST_DATE_START
        
        if [ "${POST_DATE_START}" == "" ]; then
            POST_DATE_START="${TODAY}"
        fi
        
        echo -n "Please enter post end date [only if different than post start date]: "
        read POST_DATE_END
        
        if [ "${POST_DATE_END}" == "" ]; then
            POST_DATE_END="0000-00-00"
        fi
        
        echo ""
        echo "The following information will be saved:"
        echo "Post ID: ${POST_ID}"
        echo "Post title: ${POST_TITLE}"
        echo "Post description: ${POST_DESCRIPTION}"
        echo "Post start date ${POST_DATE_START}"
        echo "Post end date: ${POST_DATE_END}"
        echo ""
        
        echo -n "Adding opening part to query... "
        echo -ne "\n-- POST INSERT QUERY\n" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo -ne "INSERT INTO r_viblog (post_id, post_title, post_description, post_date_start, post_date_end, post_active) VALUES" \
        >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
        
        echo -n "Adding query... "
        echo -ne "\n('${POST_ID}', '${POST_TITLE}', '${POST_DESCRIPTION}', '${POST_DATE_START}', '${POST_DATE_END}', '0')" \
        >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
        
        echo -n "Adding closing part to query... "
        echo -ne "\n;" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
        
    else
        echo "ERROR: No ID specified"
    fi
    
    p_footer
}

function viblog_post_activate
{
    p_header "Activate Post"
    
    echo -n "Please enter Post ID"
    if [ "${POST_ID_NEXT}" != "" ]; then
        echo -n " [next available: ${POST_ID_NEXT}] "
    fi 
    echo -n ": "
    read POST_ID
    
    if [ "$POST_ID" != "" ]; then
        
        echo -n "Execution query... "
        mysql --host="${DB_HOST}" --user="${DB_USER}" --password="${DB_PASS}" --database="${DB_NAME}" \
        --execute="UPDATE r_viblog SET post_active = 1 WHERE post_id = '${POST_ID}' LIMIT 1" -s \
        >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
        echo "Done"
        echo ""
        
    else
        echo "ERROR: No ID specified"
    fi
    
    p_footer
}

function viblog_post_deactivate
{
    p_header "Deactivate Post"
    
    echo -n "Please enter Post ID"
    if [ "${POST_ID_NEXT}" != "" ]; then
        echo -n " [next available: ${POST_ID_NEXT}] "
    fi 
    echo -n ": "
    read POST_ID
    
    if [ "$POST_ID" != "" ]; then
        
        echo -n "Execution query... "
        mysql --host="${DB_HOST}" --user="${DB_USER}" --password="${DB_PASS}" --database="${DB_NAME}" \
        --execute="UPDATE r_viblog SET post_active = 0 WHERE post_id = '${POST_ID}' LIMIT 1" -s \
        >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
        echo "Done"
        echo ""
        
    else
        echo "ERROR: No ID specified"
    fi
    
    p_footer
}
