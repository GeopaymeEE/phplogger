#!/bin/bash

RAMDISK_DIR='/tmp/phplogger_ramdisk'

if [[ $EUID -ne 0 ]]; then
    echo "This script must be run using sudo or as the root user"
    exit 1
fi

#set about 20Mbs ramdisk
mkfs -q /dev/ram1 20000

#mount in directory
mkdir -p $RAMDISK_DIR
mount /dev/ram1 $RAMDISK_DIR

#adjust permissions to your needs
chmod -R 777 $RAMDISK_DIR