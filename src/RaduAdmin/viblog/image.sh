#!/bin/bash

# $Id$

# viblog_image_get_width FILE_PATH 
function viblog_image_get_width
{
    FILE_PATH=$1
    
    exiftool -ImageWidth "${FILE_PATH}" | awk -F ": " '{print $2}'
}

# viblog_image_get_height FILE_PATH
function viblog_image_get_height
{
    FILE_PATH=$1
    
    exiftool -ImageHeight "${FILE_PATH}" | awk -F ": " '{print $2}'
}

function viblog_image_process
{
    p_header "Process images"
    
    echo -n "Please enter Post ID"
    if [ "${POST_ID_NEXT}" != "" ]; then
        echo -n " [next available: ${POST_ID_NEXT}] "
    fi 
    echo -n ": "
    read POST_ID
    
    if [ "${POST_ID}" = "" ]; then
        POST_ID=${POST_ID_PH}
        echo "WARNING: Using placeholder Post ID."
    fi
    
    echo ""
    
    echo "Will be processing input images for the post id \"$POST_ID\""
    
    echo ""
    
    echo -n "Ask for missing descriptions? [empty to skip]: "
    read ASK_DESCRIPTION
    
    if [ "${ASK_DESCRIPTION}" != "" ]; then
        echo "Will be asking for missing descriptions"
    else
        echo "Will not be asking for missing descriptions"
    fi
    
    echo ""
    
    TOTAL_IMAGES=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${IMG_EXT_IN}" | wc -l`
    echo -n "Total image files found: ${TOTAL_IMAGES}"
    echo ""
    
    if [ "${TOTAL_IMAGES}" != "0" ]; then
        
        echo -n "Creating directories... "
    
        mkdir -p "${WORKPATH}${DIR_OUT}/${POST_ID}/images"
        mkdir -p "${WORKPATH}${DIR_OUT}/${POST_ID}/thumbnails"
        
        echo "Done"
        echo ""
        
        echo "Starting file iteration"
        echo ""
        
        COUNTER=1
        echo -n "Adding opening part to query... "
        echo -ne "\n-- IMAGE INSERT QUERY\n" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo -ne "INSERT INTO r_viblog_images (post_id, image_file, image_type, image_description, image_order) VALUES" \
        >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${IMG_EXT_IN}" | sort | while read FILE;
        do
            FILE_NAME=`basename "${FILE}"`
            
            echo "Processing file \"${FILE_NAME}\""
            echo ""
            
            NEW_FILE="${FILE_NAME%.*}.${IMG_EXT_OUT}"
            
            echo -n "Resizing image... "
            convert "${FILE}" -resize ${IMG_FULL_W}x${IMG_FULL_H} \
            "${WORKPATH}${DIR_OUT}/${POST_ID}/images/${NEW_FILE}" \
            >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
            echo "Done"
            echo ""
            
            echo -n "Creating thumbnail... "
            convert "${FILE}" -thumbnail ${IMG_THUMB_W}x${IMG_THUMB_H}^ \
            -gravity center -extent ${IMG_THUMB_W}x${IMG_THUMB_W} \
            "${WORKPATH}${DIR_OUT}/${POST_ID}/thumbnails/${NEW_FILE}" \
            >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
            echo "Done"
            echo ""
            
            echo "Processing file description"
            echo ""
            
            echo -n "Searching for description file... "
            
            CAPTION=`viblog_media_get_description_file_func ${FILE}`
            
            if [ "${CAPTION}" != "" ]; then
                
                echo "Found"
                echo "Using description \"${CAPTION}\""
                
            else
                
                echo "Not found or empty"
                
                echo -n "Getting description from media file... "
                
                CAPTION=`viblog_image_get_description_meta_func "${FILE}"`
                
                if [ "${CAPTION}" != "" ]; then
                
                    echo "Found"
                    echo "Using description \"${CAPTION}\""
                
                else
                    
                    echo "Not found or empty"
                    
                    if [ "${ASK_DESCRIPTION}" != "" ]; then
                    
                        echo -n "Opening preview... ";
                        xdg-open "${FILE}" > /dev/null 2>&1
                        echo "Done"
                        echo ""
                    
                        echo -n "Last chance to enter a description for \"${FILE_NAME}\": "
                        read CAPTION < /dev/tty
                    fi
                    
                    if [ "${CAPTION}" == "" ]; then
                        echo "Empty description, using file name instead."
                        CAPTION=${NEW_FILE%.*}
                    fi
                    
                fi	
            fi
            
            echo -n "Adding query... "
            echo -ne "\n('${POST_ID}', '${NEW_FILE%.*}', '${NEW_FILE##*.}', '${CAPTION}', '${COUNTER}')" \
            >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
            echo "Done"
            echo ""
            
            if [ "${COUNTER}" != "${TOTAL_IMAGES}" ]; then
                echo -n "Adding closing semicolon to query... "
                echo -ne "," >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
                echo "Done"
                echo ""
            fi
            
            let COUNTER=COUNTER+1
        
        done
        echo -n "Adding closing part to query... "
        echo -ne "\n;" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
    else
        echo "No images to process"
    fi
    
    p_footer
}

# viblog_viblog_image_add_description_meta_func FILE_NAME DESCRIPTION
function viblog_viblog_image_add_description_meta_func
{
    FILE_NAME=$1
    DESCRIPTION=$2
    
    exiftool -ImageDescription="${DESCRIPTION}" \
    "${WORKPATH}${DIR_IN}/${FILE_NAME}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
}

function viblog_image_add_description_meta
{
    p_header "Add description [meta] to image"
    
    echo -n "File name: "
    read FILE_NAME
    
    if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
        
        echo ""
        echo -n "Opening preview... ";
        xdg-open "${WORKPATH}${DIR_IN}/${FILE_NAME}" > /dev/null 2>&1
        echo "Done"
        echo ""
    
        echo -n "Please enter description for \"${FILE_NAME}\": "
        read DESCRIPTION
        
        echo -n "Writing description to file... "
        viblog_viblog_image_add_description_meta_func "${FILE_NAME}" "${DESCRIPTION}"
        echo "Done"
        echo ""
            
    else
        echo "ERROR: File not found"
    fi
    
    p_footer
}

function viblog_image_add_description_meta_all
{
    p_header "Add description [meta] to image [all]"
    
    echo -n "File extension to use: [leave blank to use default extension \"${IMG_EXT_IN}\"]"
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
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | sort | while read FILE;
        do
            
            FILE_NAME=`basename "${FILE}"`
            
            echo -n "Opening preview... ";
            xdg-open "${WORKPATH}${DIR_IN}/${FILE_NAME}" > /dev/null 2>&1
            echo "Done"
            echo ""
            
            echo -n "Please enter description for \"${FILE_NAME}\": "
            read DESCRIPTION < /dev/tty
            
            echo ""
        
            echo -n "Writing description to file... "
            viblog_viblog_image_add_description_meta_func "${FILE_NAME}" "${DESCRIPTION}"
            echo "Done"
            echo ""
        done
        
    else
        echo "No files to process"
    fi
    
    p_footer
}

# viblog_image_get_description_meta_func FILE_PATH
function viblog_image_get_description_meta_func
{
    FILE_PATH=$1
    
    exiftool -ImageDescription "${FILE_PATH}" | awk -F ": " '{print $2}'
}

function viblog_image_get_description_all
{
    p_header "Display description [meta and file] of images [all]"
    
    echo -n "File extension to use: [leave blank to use default extension \"${IMG_EXT_IN}\"]"
    read FILE_EXT
    
    if [ "${FILE_EXT}" = "" ]; then
        FILE_EXT=${IMG_EXT_IN}
    fi
    
    echo "Using file extension \"${FILE_EXT}\""
    
    TOTAL_FILES=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | wc -l`
    echo -n "Total files files found: ${TOTAL_FILES}"
    
    echo ""
    
    if [ "${TOTAL_FILES}" != "0" ]; then
        
        echo "Starting file iteration"
    
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | sort | while read FILE;
        do
            
            FILE_NAME=`basename "${FILE}"`
            
            echo "File \"${FILE_NAME}\""
            echo -n "File description: "
            echo `viblog_media_get_description_file_func "${FILE}"`
            echo -n "Meta description: "
            echo `viblog_image_get_description_meta_func "${FILE}"`
            echo ""
            
        done
        
    else
        echo "No files to display"
    fi
    
    p_footer
}