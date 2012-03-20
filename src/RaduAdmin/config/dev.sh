#!/bin/bash

# $Id$

#path
WORKPATH='/home/radu/Documents/workspace/wsRadu/tmp/'
DIR_IN='viblog_in'
DIR_OUT='viblog_out'
DIR_TMP='viblog_tmp'
LOG_FILE='VIBlog.log'

# post
POST_ID_PH='_POST_ID_'
POST_ID_NEXT=''

# image
IMG_EXT_IN='jpg'
IMG_EXT_OUT='jpg'
IMG_THUMB_W='96'
IMG_THUMB_H='96'
IMG_FULL_W='800'
IMG_FULL_H='800'

# video
VID_EXT_IN='avi'
VID_EXT_PROCESS='mp4'
VID_EXT_OUT='mp4'
VID_FADE_DURATION='60'
VID_RESIZE_W='480'
VID_THUMB_TIME='00:03'
VID_ENCODE_BR='5000k'
VID_OUT_BR='900k'

# media
META_EXT='meta'

# database
DB_HOST='localhost'
DB_USER='example'
DB_PASS='REDACTED'
DB_NAME='example'

# ftp
FTP_HOST='localhost'
FTP_USER='example'
FTP_PASS='REDACTED'
FTP_PATH_ASSETS='/Documents/workspace/wsRadu/src/RaduApp/web/usr/viblog/assets/'