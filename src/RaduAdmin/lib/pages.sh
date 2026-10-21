#!/bin/bash

# $Id$

function page_help
{
    clear
    dialog --title "${P_NAME} Help" \
    --msgbox "\n Fear not!\n Help is on the way." 20 60
}

function page_about
{
    clear
    dialog --title "About ${P_NAME}" \
    --msgbox "\n Name: ${P_NAME}\n Version ${P_VERSION}" 20 60
}