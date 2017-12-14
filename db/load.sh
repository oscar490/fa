#!/bin/sh

sudo -u postgres psql -h localhost -U fa -d fa < fa.sql
