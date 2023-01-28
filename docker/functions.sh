#!/usr/bin/env bash

declare -a C_NAMES_ARRAY=("DOCKER_IMAGE"
                    "CONTAINER_NAME"
                    "HOST_PORT")

declare -a HD_DIRS_ARRAY=("HD_SRC_DIR"
                    "HD_CONFIG_DIR"
                    "HD_DATA_DIR"
                    "HD_PUBLIC_DIR"
                    "HD_LOGS_DIR"
                    "HD_ZZZ_DIR"
                    "HD_VENDOR_DIR")


B_RED="\E[1;41m"
B_LGRAY="\E[1;47m"
C_WHITE="\E[1;37m"
C_END="\E[0m "

function checkConstants() {
    constants="$1"
    echo "function ${FUNCNAME[0]} "
    if [[ ! -f "$constants" ]]; then
      printf "\nConstants file $C_WHITE $B_RED %s $C_END does not exist\n" "$constants"
      exit 1
    fi
    # shellcheck source=/dev/null
    . $constants

    all_vars+=( "${C_NAMES_ARRAY[@]}" "${HD_DIRS_ARRAY[@]}" )
    for const in "${all_vars[@]}"; do
      value=${!const}
      if [[ ! $value ]]; then
        printf "\nVariable $C_WHITE $B_RED %s $C_END not set or not found\n" "$const"
        exit 1
      fi
      echo -n "."
    done

    for const in "${HD_DIRS_ARRAY[@]}"; do
      value=${!const}
      if [[ ! -d "$value" ]]; then
        printf "\nHost directory not found:\n %s ->$C_WHITE $B_RED %s $C_END\n" "$const" "$value"
        exit 1
      fi
      echo -n "."
    done
    echo
}

function isDockerInstalled() {
  echo "function ${FUNCNAME[0]} "
  if ! command -v docker &> /dev/null
  then
      printf "\n$C_WHITE $B_RED docker not installed %s$C_END" ""
      echo "Aborting"
      exit 1
  else
    echo "-- docker installed: $(which docker)"
    echo "-- $(docker --version)"
  fi
}

function maybeBuildImage() {
  echo "function ${FUNCNAME[0]} "
  if [[ "$(docker images -q $DOCKER_IMAGE 2> /dev/null)" == "" ]]; then
    echo "-- Image $DOCKER_IMAGE does not exists"
    echo "-- Do you want to build image $DOCKER_IMAGE? y/N"
    read answer
    if [ "$answer" == "y" ]; then
      buildImage
    else
      echo "User aborted"
      exit 1
    fi
  else
    echo "-- Image $DOCKER_IMAGE found"
  fi
}

function buildImage() {
  echo "function ${FUNCNAME[0]} "
  echo "-- Building image $DOCKER_IMAGE"
  # docker build -t $DOCKER_IMAGE .
}

function testContainerStatus() {
  echo "function ${FUNCNAME[0]} "
  status="$(docker container inspect -f '{{.State.Status}}' $CONTAINER_NAME)"
  if [[ ! $status ]]; then
    printf "\nContainer$C_WHITE $B_RED %s $C_END not found\n" $CONTAINER_NAME
    echo "Aborting"
    exit 1
  fi
  echo "-- Status of container '$CONTAINER_NAME': $status"
  if [ "$status" == "running" ]; then
    echo "-- Stop container before restarting"
    echo "-- Aborting"
    exit 1
  fi
  if [ "$status" == "exited" ]; then
    echo -n "-- Removing container "
    docker rm "$CONTAINER_NAME"
  fi
}

function runContainer() {
  echo "function ${FUNCNAME[0]} "

  echo -e "-- Starting server with linked directories"
  echo -e "\t /var/www/app \t\t-> $HD_SRC_DIR"
  echo -e "\t /var/www/config \t-> $HD_CONFIG_DIR"
  echo -e "\t /var/www/data \t\t-> $HD_DATA_DIR"
  echo -e "\t /var/www/html \t\t-> $HD_PUBLIC_DIR"
  echo -e "\t /var/www/logs \t\t-> $HD_LOGS_DIR"
  echo -e "\t /var/www/zzz \t\t-> $HD_ZZZ_DIR"
  echo -e "\t /var/vendor \t\t-> $HD_VENDOR_DIR"
  echo -e "-- "

  docker run -d \
  	--name $CONTAINER_NAME \
  	-v $HD_SRC_DIR:/var/www/app \
  	-v $HD_CONFIG_DIR:/var/www/config \
  	-v $HD_DATA_DIR:/var/www/data \
  	-v $HD_PUBLIC_DIR:/var/www/html \
  	-v $HD_LOGS_DIR:/var/www/logs \
  	-v $HD_ZZZ_DIR:/var/www/zzz \
  	-v $HD_VENDOR_DIR:/var/vendor \
  	-p $HOST_PORT:80 \
  	$DOCKER_IMAGE

  if [ $? -eq 0 ]; then
  	echo "-- Server started"
  	printf "   To stop the server type $B_LGRAY docker stop %s $C_END\n" $CONTAINER_NAME
  else
  	echo "## FAIL ######## see above for details ########"
  	exit
  fi
  echo
  echo "-- docker ps ------------------------------------------------"
  docker ps
  echo "-------------------------------------------------------------"
  echo -e "\n"
}

function findAddress() {
  echo "function ${FUNCNAME[0]} "

  # when function called with ./functions.sh findAddress
  # we don't know the host port
  if [ ! "$HOST_PORT" ]; then HOST_PORT="[unknown]"; fi
  if [ ! "$CONTAINER_NAME" ]; then CONTAINER_NAME="[unknown]"; fi

  addr=$(ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p')
  echo "-------------------------------------------------------------"
  printf "%s on $B_LGRAY http://localhost:%s $C_END\n" $CONTAINER_NAME $HOST_PORT
  [ -z "$addr" ] && echo "--" || echo -e "\n-- on network\n$addr:$HOST_PORT"
  echo "-------------------------------------------------------------"
  echo -e "\n"
}

function maybeFollowLogs() {
  echo "function ${FUNCNAME[0]} "
  echo "-- Do you want to follow server logs of $CONTAINER_NAME? y/N"
      read -t 10 answer
      if [ "$answer" != "n" ]; then
        followLogs
      fi
      echo "-- the end --"
}

function followLogs() {
  echo "function ${FUNCNAME[0]} "
  printf "   To stop following the logs type $B_LGRAY Ctrl+c %s $C_END\n" ""
  docker logs -f $CONTAINER_NAME
}

"$@"