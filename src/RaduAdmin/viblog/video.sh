#!/bin/bash

# $Id$

# viblog_video_get_width FILE_PATH 
function viblog_video_get_width
{
    FILE_PATH=$1
    
    VALUE=`exiftool -SourceImageWidth "${FILE_PATH}" | awk -F ": " '{print $2}'`
    
    if [ "${VALUE}" == "" ]; then
        
        VALUE=`exiftool -ImageWidth "${FILE_PATH}" | awk -F ": " '{print $2}'`
    fi
    
    echo ${VALUE}
}

# viblog_video_get_height FILE_PATH
function viblog_video_get_height
{
    FILE_PATH=$1
    
    VALUE=`exiftool -SourceImageHeight "${FILE_PATH}" | awk -F ": " '{print $2}'`
    
    if [ "${VALUE}" == "" ]; then
        
        VALUE=`exiftool -ImageHeight "${FILE_PATH}" | awk -F ": " '{print $2}'`
    fi
    
    echo ${VALUE}
}

function viblog_video_cut
{
    p_header "Cut video"
    
    CUT_DURATION=''
    
    echo -n "File name: "
    read FILE_NAME
    if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
        
        echo -n "Start time (HH:MM:SS): "
        read CUT_START
        
        echo -n "End time (HH:MM:SS) [leave empty to specify duration instead]: "
        read CUT_END
        if [ "$CUT_END" = "" ]; then
            
            echo -n "Duration (SS): "
            read CUT_DURATION
        fi
        
        if [ "$CUT_DURATION" = "" ]; then
            
            T_START=`date --date "${CUT_START}" +%s`
            T_END=`date --date "${CUT_END}" +%s`
            T_DIFF=$(( ${T_END} - ${T_START} ))
            T_H=`date -u --date="1970-01-01 ${T_DIFF} sec GMT" +%H`
            T_M=`date -u --date="1970-01-01 ${T_DIFF} sec GMT" +%M`
            T_S=`date -u --date="1970-01-01 ${T_DIFF} sec GMT" +%S`
            
            CUT_DURATION=$(( (60 * 60 * ${T_H}) + (60 * ${T_M}) + ${T_S} ))
            
        fi
        
        echo "Renaming original file"
        mv "${WORKPATH}${DIR_IN}/${FILE_NAME}" "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${FILE_NAME##*.}_ORIGINAL"
        
        echo "Cutting file"
        ffmpeg < /dev/null -y -ss ${CUT_START} -t ${CUT_DURATION} \
        -i "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${FILE_NAME##*.}_ORIGINAL" \
        -vcodec copy -acodec copy \
        "${WORKPATH}${DIR_IN}/${FILE_NAME}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
        
    else
        echo "ERROR: File not found"
    fi
    
    p_footer
}

# viblog_viblog_video_fade_func FILE_NAME FADE_TIME VIDEO_RESIZE_WIDTH VIDEO_RESIZE_HEIGHT
function viblog_viblog_video_fade_func
{
    FILE_NAME=$1
    FADE_DURATION=$2
    VIDEO_RESIZE_WIDTH=$3
    VIDEO_RESIZE_HEIGHT=$4
    
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Getting frame count of video ${FILE_NAME}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    TOTAL_FRAMES=`ffmpeg < /dev/null -i "${WORKPATH}${DIR_IN}/${FILE_NAME}" -vcodec copy -f rawvideo -y /dev/null 2>&1 | tr ^M '\n' | awk '/^frame=/ {print $2}'|tail -n 1`
    echo -e "Frame count for video ${FILE_NAME} is ${TOTAL_FRAMES}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    FADE_OUT_FRAME=$(( ${TOTAL_FRAMES} - ${FADE_DURATION} ))
    echo -e "Fade out frame for video ${FILE_NAME} is ${FADE_OUT_FRAME}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    echo -e "Adding fade effect to video ${FILE_NAME} for ${FADE_DURATION} frames" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    ffmpeg < /dev/null -y -i "${WORKPATH}${DIR_IN}/${FILE_NAME}" \
    -vf "fade=in:0:${FADE_DURATION}, fade=out:${FADE_OUT_FRAME}:${FADE_DURATION}" \
    -s ${VIDEO_RESIZE_WIDTH}x${VIDEO_RESIZE_HEIGHT} -aspect 3:2 \
    -vcodec mpeg4 -vb ${VID_ENCODE_BR} -r 30 \
    -acodec aac -strict experimental -ac 2 -ar 44100 -ab 128k \
    "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${VID_EXT_OUT}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
}

function viblog_video_fade
{
    p_header "Add fade in & out effect to video"
    
    echo -n "File name: "
    read FILE_NAME
    
    if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
        
            echo -n "Fade duration (frames) [leave empty to use default of \"${VID_FADE_DURATION}\"]: "
            read FADE_DURATION
            if [ "$FADE_DURATION" = "" ]; then
                FADE_DURATION=${VID_FADE_DURATION}
            fi
            
            echo -n "Adding fade effect to video ${FILE_NAME} for ${FADE_DURATION} frames... "
            VIDEO_WIDTH=`viblog_video_get_width "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_HEIGHT=`viblog_video_get_height "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_RESIZE_WIDTH=${VID_RESIZE_W}
            VIDEO_RESIZE_HEIGHT=`viblog_get_media_resize_height ${VIDEO_WIDTH} ${VIDEO_HEIGHT} ${VIDEO_RESIZE_WIDTH}`
            viblog_viblog_video_fade_func "${FILE_NAME}" ${FADE_DURATION} ${VIDEO_RESIZE_WIDTH} ${VIDEO_RESIZE_HEIGHT}
            echo "Done"
            echo ""
        
    else
        echo "ERROR: File not found"
    fi
    
    p_footer
}

function viblog_video_fade_all
{
    p_header "Add fade effect to all input videos and encode to prepare for processing"
    
    echo -n "File extension to encode: [leave blank to use default \"${VID_EXT_IN}\"]: "
    read VIDEO_EXT
    
    if [ "$VIDEO_EXT" = "" ]; then
        VIDEO_EXT=${VID_EXT_IN}
    fi
    
    echo "Using file extension \"${VIDEO_EXT}\""
    
    TOTAL_VIDEOS=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | wc -l`
    echo -n "Total video files found: ${TOTAL_VIDEOS}"
    
    echo ""
    
    if [ "${TOTAL_VIDEOS}" != "0" ]; then
        
        echo -n "Fade duration (frames) [leave empty to use default of \"${VID_FADE_DURATION}\"]: "
        read FADE_DURATION
        if [ "$FADE_DURATION" = "" ]; then
            FADE_DURATION=${VID_FADE_DURATION}
        fi
    
        echo "Starting file iteration..."
    
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | sort | while read FILE;
        do
            FILE_NAME=`basename "${FILE}"`
            
            echo -n "Encoding and adding fade effect to video \"${FILE_NAME}\" for \"${FADE_DURATION}\" frames... "
            VIDEO_WIDTH=`viblog_video_get_width "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_HEIGHT=`viblog_video_get_height "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_RESIZE_WIDTH=${VID_RESIZE_W}
            VIDEO_RESIZE_HEIGHT=`viblog_get_media_resize_height ${VIDEO_WIDTH} ${VIDEO_HEIGHT} ${VIDEO_RESIZE_WIDTH}`
            viblog_viblog_video_fade_func "${FILE_NAME}" ${FADE_DURATION} ${VIDEO_RESIZE_WIDTH} ${VIDEO_RESIZE_HEIGHT}
            echo "Done"
            echo ""
        done
    
    else
        echo "No videos to process"
    fi
    
    p_footer
}

# viblog_viblog_video_encode_func FILE_NAME VIDEO_RESIZE_WIDTH VIDEO_RESIZE_HEIGHT
function viblog_viblog_video_encode_func
{
    FILE_NAME=$1
    VIDEO_RESIZE_WIDTH=$2
    VIDEO_RESIZE_HEIGHT=$3
    
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Encoding video \"${FILE_NAME}\"" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    ffmpeg < /dev/null -y -i "${WORKPATH}${DIR_IN}/${FILE_NAME}" \
    -s ${VIDEO_RESIZE_WIDTH}x${VIDEO_RESIZE_HEIGHT} -aspect 3:2 \
    -vcodec mpeg4 -vb ${VID_ENCODE_BR} -r 30 \
    -acodec aac -strict experimental -ac 2 -ar 44100 -ab 128k \
    "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${VID_EXT_OUT}" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
}

function viblog_video_encode
{
    p_header "Encode video to prepare for processing"
    
    echo -n "File name: "
    read FILE_NAME
    
    if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
        
            echo -n "Encoding video \"${FILE_NAME}\"... "
            VIDEO_WIDTH=`viblog_video_get_width "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_HEIGHT=`viblog_video_get_height "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_RESIZE_WIDTH=${VID_RESIZE_W}
            VIDEO_RESIZE_HEIGHT=`viblog_get_media_resize_height ${VIDEO_WIDTH} ${VIDEO_HEIGHT} ${VIDEO_RESIZE_WIDTH}`
            viblog_viblog_video_encode_func "${FILE_NAME}" ${VIDEO_RESIZE_WIDTH} ${VIDEO_RESIZE_HEIGHT}
            echo "Done"
            echo ""
        
    else
        echo "ERROR: File not found"
    fi
    
    p_footer
}

function viblog_video_encode_all
{
    p_header "Encode all input videos to prepare for processing"
    
    echo -n "File extension to encode: [leave blank to use default \"${VID_EXT_IN}\"]"
    read VIDEO_EXT
    
    if [ "$VIDEO_EXT" = "" ]; then
            VIDEO_EXT=${VID_EXT_IN}
    fi
    
    echo "Using file extension \"${VIDEO_EXT}\""
    
    TOTAL_VIDEOS=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | wc -l`
    echo -n "Total video files found: ${TOTAL_VIDEOS}"
    
    echo ""
    
    if [ "${TOTAL_VIDEOS}" != "0" ]; then
    
        echo "Starting file iteration..."
    
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | sort | while read FILE;
        do
            FILE_NAME=`basename "${FILE}"`
            
            echo -n "Encoding video \"${FILE_NAME}\"... "
            VIDEO_WIDTH=`viblog_video_get_width "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_HEIGHT=`viblog_video_get_height "${WORKPATH}${DIR_IN}/${FILE_NAME}"`
            VIDEO_RESIZE_WIDTH=${VID_RESIZE_W}
            VIDEO_RESIZE_HEIGHT=`viblog_get_media_resize_height ${VIDEO_WIDTH} ${VIDEO_HEIGHT} ${VIDEO_RESIZE_WIDTH}`
            viblog_viblog_video_encode_func "${FILE_NAME}" ${VIDEO_RESIZE_WIDTH} ${VIDEO_RESIZE_HEIGHT}
            echo "Done"
            echo ""
        done
    
    else
        echo "No videos to process"
    fi
    
    p_footer
}

# viblog_viblog_video_process_func POST_ID FILE_NAME VIDEO_RESIZE_WIDTH 
function viblog_viblog_video_process_func
{
    POST_ID=$1
    FILE_NAME=$2
    VIDEO_RESIZE_WIDTH=$3
    
    OUT_FILE_PATH_NO_EXT="${WORKPATH}${DIR_OUT}/${POST_ID}/videos/${FILE_NAME%.*}"
    
    # 1
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Encoding (#1) video \"${FILE_NAME}\"" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    HandBrakeCLI < /dev/null -i "${WORKPATH}${DIR_IN}/${FILE_NAME}" \
    -o "${OUT_FILE_PATH_NO_EXT}.${VID_EXT_OUT}" \
    -O -e x264 -q 20.0 -a 1 -E faac -B 128 -6 dpl2 -R Auto -D 0.0 \
    -f mp4 -X ${VIDEO_RESIZE_WIDTH} \
    -m -x cabac=0:ref=2:me=umh:bframes=0:weightp=0:subme=6:8x8dct=0:trellis=0 \
    >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    # 2
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Encoding (#2) video \"${FILE_NAME}\"" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    ffmpeg < /dev/null -i "${OUT_FILE_PATH_NO_EXT}.${VID_EXT_OUT}" \
    -b ${VID_OUT_BR} -vcodec libvpx -acodec libvorbis -ab 128k -f webm -g 13 \
    "${OUT_FILE_PATH_NO_EXT}.webm" \
    >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    # 3
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Encoding (#3) video \"${FILE_NAME}\"" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    ffmpeg < /dev/null -i "${OUT_FILE_PATH_NO_EXT}.${VID_EXT_OUT}" \
    -b ${VID_OUT_BR} -vcodec libtheora -acodec libvorbis -ab 128k -g 13 \
    "${OUT_FILE_PATH_NO_EXT}.ogv" \
    >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    
    # 4
    echo -e " " >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    echo -e "Encoding (#4) video \"${FILE_NAME}\"" >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
    ffmpeg < /dev/null -i "${OUT_FILE_PATH_NO_EXT}.${VID_EXT_OUT}" \
    -ss ${VID_THUMB_TIME} -vframes 1 -r 1 -f image2 \
    "${OUT_FILE_PATH_NO_EXT}.jpg" \
    >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
}

function viblog_video_process
{
    p_header "Process videos"
    
    echo -n "Please enter Post ID"
    if [ "${POST_ID_NEXT}" != "" ]; then
        echo -n " [next available: ${POST_ID_NEXT}] "
    fi 
    echo -n ": "
    read POST_ID
    if [ "$POST_ID" = "" ]; then
        POST_ID=$POST_ID_PH
        echo "WARNING: Using placeholder Post ID."
    fi
    
    echo -n "Video extension to process: [leave blank to use default \"${VID_EXT_PROCESS}\"]: "
    read VIDEO_EXT
    if [ "${VIDEO_EXT}" = "" ]; then
        VIDEO_EXT=${VID_EXT_PROCESS}
    fi
    
    echo ""
    
    echo "Will be processing input videos of type \"${VIDEO_EXT}\", for the post id \"$POST_ID\""
    
    echo ""
    
    echo -n "Ask for missing descriptions? [empty to skip]: "
    read ASK_DESCRIPTION
    
    if [ "${ASK_DESCRIPTION}" != "" ]; then
        echo "Will be asking for missing descriptions"
    else
        echo "Will not be asking for missing descriptions"
    fi
    
    echo ""
    
    TOTAL_VIDEOS=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | wc -l`
    echo -n "Total video files found: ${TOTAL_VIDEOS}"
    
    echo ""
    
    if [ "${TOTAL_VIDEOS}" != "0" ]; then
        
        echo -n "Creating directories... "
        
        mkdir -p "${WORKPATH}${DIR_OUT}/${POST_ID}/videos"
        
        echo "Done"
        echo ""
        
        echo "Starting file iteration"
        echo ""
        
        COUNTER=1
        echo -n "Adding opening part to query... "
        echo -ne "\n-- VIDEO INSERT QUERY\n" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo -ne "INSERT INTO r_viblog_videos (post_id, video_file, video_type, video_description, video_order) VALUES" \
        >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
        echo "Done"
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${VIDEO_EXT}" | sort | while read FILE;
        do
            FILE_NAME=`basename "${FILE}"`
            
            echo -n "Processing video \"${FILE_NAME}\"... "
            
            viblog_viblog_video_process_func ${POST_ID} "${FILE_NAME}" ${VID_RESIZE_W}
            
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
                
                CAPTION=`viblog_video_get_description_meta_func "${FILE}"`
                
                if [ "${CAPTION}" != "" ]; then
                
                    echo "Found"
                    echo "Using description \"${CAPTION}\""
                
                else
                    
                    echo "Not found or empty"
                    
                    if [ "${ASK_DESCRIPTION}" != "" ]; then
                    
                        echo -n "Opening preview... ";
                        xdg-open "${FILE}" > /dev/null 2>&1
                        echo "Done"
                    
                        echo -n "Last chance to enter a description for \"${FILE_NAME}\": "
                        read CAPTION < /dev/tty
                    fi
                    
                    if [ "${CAPTION}" == "" ]; then
                        echo "Empty description, using file name instead."
                        CAPTION=${FILE_NAME%.*}
                    fi
                    
                fi	
            fi
            
            echo -n "Adding query... "
            echo -ne "\n('${POST_ID}', '${FILE_NAME%.*}', '${VID_EXT_OUT}', '${CAPTION}', '${COUNTER}')" >> "${WORKPATH}${DIR_OUT}/${POST_ID}.sql"
            echo "Done"
            echo ""
            
            if [ "${COUNTER}" != "${TOTAL_VIDEOS}" ]; then
                echo -n "Adding closing comma to query... "
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
        echo "No videos to process"
    fi
    
    p_footer
}

# viblog_viblog_video_add_description_meta_func FILE_NAME DESCRIPTION
function viblog_viblog_video_add_description_meta_func
{
    FILE_NAME=$1
    DESCRIPTION=$2
    
    mv "${WORKPATH}${DIR_IN}/${FILE_NAME}" "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${FILE_NAME##*.}_ORIGINAL"
    
    ffmpeg < /dev/null -y -i "${WORKPATH}${DIR_IN}/${FILE_NAME%.*}.${FILE_NAME##*.}_ORIGINAL" \
    -metadata comment="${DESCRIPTION}" \
    -vcodec copy -acodec copy \
    "${WORKPATH}${DIR_IN}/${FILE_NAME}" \
    >> "${WORKPATH}${DIR_TMP}/${LOG_FILE}" 2>&1
}

function viblog_video_add_description_meta
{
    p_header "Add description [meta] to video"
    
    echo -n "File name: "
    read FILE_NAME
    
    if [ -f "${WORKPATH}${DIR_IN}/${FILE_NAME}" ]; then
        
        echo -n "Opening preview... ";
        xdg-open "${WORKPATH}${DIR_IN}/${FILE_NAME}" > /dev/null 2>&1
        echo "Done"
        echo ""
    
        echo -n "Please enter description for \"${FILE_NAME}\": "
        read DESCRIPTION
        
        echo -n "Writing description to file... "
        viblog_viblog_video_add_description_meta_func "${FILE_NAME}" "${DESCRIPTION}"
        echo "Done"
        echo ""
            
    else
        echo "ERROR: File not found"
    fi
    
    p_footer
}

function viblog_video_add_description_meta_all
{
    p_header "Add description [meta] to video [all]"
    
    echo -n "File extension to use: [leave blank to use default extension \"${VID_EXT_PROCESS}\"]"
    read FILE_EXT
    
    if [ "${FILE_EXT}" = "" ]; then
        FILE_EXT=${VID_EXT_PROCESS}
    fi
    
    echo "Using file extension \"${FILE_EXT}\""
    
    TOTAL_FILES=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | wc -l`
    echo -n "Total files files found: ${TOTAL_FILES}"
    
    echo ""
    
    if [ "${TOTAL_FILES}" != "0" ]; then
        
        echo "Starting file iteration..."
    
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | sort | while read FILE;
        do
            
            FILE_NAME=`basename "${FILE}"`
            
            echo -n "Opening preview... ";
            xdg-open "${FILE}" > /dev/null 2>&1
            echo "Done"
            echo ""
            
            echo -n "Please enter description for \"${FILE_NAME}\": "
            read DESCRIPTION < /dev/tty
        
            echo -n "Writing description to file... "
            viblog_viblog_video_add_description_meta_func "${FILE_NAME}" "${DESCRIPTION}"
            echo "Done"
            echo ""
        done
        
    else
        echo "No files to process"
    fi
    
    p_footer
}

# viblog_video_get_description_meta_func FILE_PATH
function viblog_video_get_description_meta_func
{
    FILE_PATH=$1
    
    exiftool -Comment "${FILE_PATH}" | awk -F ": " '{print $2}'
}

function video_get_description_all
{
    p_header "Display description [meta and file] of videos [all]"
    
    echo -n "File extension to use: [leave blank to use default extension \"${VID_EXT_PROCESS}\"]"
    read FILE_EXT
    
    if [ "${FILE_EXT}" = "" ]; then
        FILE_EXT=${VID_EXT_PROCESS}
    fi
    
    echo "Using file extension \"${FILE_EXT}\""
    
    TOTAL_FILES=`find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | wc -l`
    echo -n "Total files files found: ${TOTAL_FILES}"
    
    echo ""
    
    if [ "${TOTAL_FILES}" != "0" ]; then
        
        echo "Starting file iteration..."
    
        echo ""
        
        find "${WORKPATH}${DIR_IN}" -type f -iname "*.${FILE_EXT}" | sort | while read FILE;
        do
            
            FILE_NAME=`basename "${FILE}"`
            
            echo "File \"${FILE_NAME}\""
            echo -n "File description: "
            echo `viblog_media_get_description_file_func ${FILE}`
            echo -n "Meta description: "
            echo `viblog_video_get_description_meta_func "${FILE}"`
            echo ""
            
        done
        
    else
        echo "No files to display"
    fi
    
    p_footer
}
