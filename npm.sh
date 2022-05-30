#!/bin/bash

exec docker run --rm --tty -v "$PWD"/src:/usr/src/app -w /usr/src/app node:14.15.4-alpine3.10 npm $*