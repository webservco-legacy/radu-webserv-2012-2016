#!/bin/bash

# $Id$

P_PATH='/home/radu/Documents/workspace/wsRadu/src/RaduAdmin/'
P_ENV=`cat "${P_PATH}config/.env"`
P_NAME="$(basename $0) [${P_ENV}]"
P_VERSION='$Revision$'
P_USER=$USER


. "${P_PATH}config/${P_ENV}.sh"

. "${P_PATH}menu/gui.sh"
. "${P_PATH}menu/menu_main.sh"
. "${P_PATH}menu/menu_pages.sh"

. "${P_PATH}lib/functions.sh"
. "${P_PATH}lib/pages.sh"

. "${P_PATH}viblog/files.sh"
. "${P_PATH}viblog/image.sh"
. "${P_PATH}viblog/media.sh"
. "${P_PATH}viblog/post.sh"
. "${P_PATH}viblog/server.sh"
. "${P_PATH}viblog/video.sh"

#. "${P_PATH}viblog/*.sh"

check_user

if [ "$1" != "" ]; then
    while [ "$1" != "" ]; do
        case $1 in
            -h | --help )	page_help
                            exit_ok
                            ;;
                            
            -a | --about )	page_about
                            exit_ok
                            ;;
                            
            * )				show_gui
        esac
    done
else
    show_gui
fi