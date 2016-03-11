#!/bin/bash
if [ "$OLD_PATH" = "" ]; then
	export OLD_PATH=$PATH
	PATH="$PWD/bin:$PWD/vendor/bin:$PATH"
	echo "New path is ready to go"
else
	PATH=$OLD_PATH
	OLD_PATH=""
	echo "Project path is deleted"
fi
