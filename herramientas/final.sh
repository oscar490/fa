#!/bin/bash

git checkout master
git pull
rama=$(git branch | tail -n1 | cut -f3 -d" ")
git branch -d $rama
git remote prune origin
