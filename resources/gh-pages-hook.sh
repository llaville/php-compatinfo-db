#!/usr/bin/env bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

ASSETS_IMAGE_DIR="docs/assets/images"

php $SCRIPT_DIR/graph-uml/build.php application-command $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php application-event $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php application-query $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php application-service $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php domain-factory $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php domain-repository $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php domain-valueobject $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php infrastructure-bus $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php infrastructure-framework $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php infrastructure-persistence $ASSETS_IMAGE_DIR
php $SCRIPT_DIR/graph-uml/build.php presentation-console $ASSETS_IMAGE_DIR
