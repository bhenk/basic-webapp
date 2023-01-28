#!/usr/bin/env bash
# -------------------------------------------------------------------
# Script to start a docker container.
#
# This script uses environment variables as defined in the file
# constants.env
#
# If a docker image with a name as held in the environment
# variable DOCKER_IMAGE was not found, the script will try
# to build an image as described in the file Dockerfile.
#
# Usage:
# start_server [options]
# Options :
#   -c File : Read environment variables from the given file.
#             The file must enlist the same environment
#             variables as listed in constants.env
#             Default: constants.env
#
# -------------------------------------------------------------------
while getopts c: flag
do
    case "${flag}" in
        c) constants=${OPTARG};;
        *) echo; exit 1;;
    esac
done
if [ -z ${constants+x} ]; then constants=constants.env; fi;
printf "\n\E[1;47mSetting up server with constants file: %s \E[0m\n" $constants;

cd "$(dirname $0)" || exit

source functions.sh

checkConstants $constants
isDockerInstalled
maybeBuildImage
testContainerStatus
runContainer
findAddress
maybeFollowLogs
