#!/usr/bin/env bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

ASSETS_IMAGE_DIR="docs/assets/images"

php $SCRIPT_DIR/build.php graph-composer $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/build.php command-line-runner_application $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/build.php command-line-runner_presentation $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/build.php configuration_application $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/build.php event_application $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/build.php kernel_application $ASSETS_IMAGE_DIR
