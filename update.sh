#!/bin/bash

git stash
git pull github master
npm install
grunt deploy
rm -rf node_modules