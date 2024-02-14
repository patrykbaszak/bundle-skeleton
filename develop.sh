#!/bin/bash

target_dir="app"

if [ -d "$target_dir" ]; then
    sudo rm -rf "$target_dir"
fi

mkdir "$target_dir"

shopt -s extglob dotglob
cp -R !(.|..|app|.git|.env.local|node_modules|vendor|.dockerfile_checksum|*-lock.json) "$target_dir"

cd "$target_dir" && bash start.sh vendor/package
