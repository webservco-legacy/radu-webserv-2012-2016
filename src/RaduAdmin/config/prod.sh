#!/bin/bash

# $Id: prod.sh 155 2015-04-19 10:57:12Z radu $

#path
WORKPATH='/home/radu/workspace/ws_Radu/tmp/'
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
IMG_FULL_W='1280'
IMG_FULL_H='1280'

# video
VID_EXT_IN='avi'
VID_EXT_PROCESS='mp4'
VID_EXT_OUT='mp4'
VID_FADE_DURATION='60'
VID_RESIZE_W='853'
VID_THUMB_TIME='00:00:07'
VID_ENCODE_BR='5000k'
VID_OUT_BR='900k'

# media
META_EXT='meta'

# database
DB_HOST='radu.webserv.co'
DB_USER='example'
DB_PASS='REDACTED'
DB_NAME='example'

# ftp
FTP_HOST='radu.webserv.co'
FTP_USER='example'
FTP_PASS='REDACTED'
FTP_PATH_ASSETS='/web/usr/assets/viblog/'