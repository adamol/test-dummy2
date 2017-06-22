#!/bin/bash

docker run -v /home/adam.olsson/code/test-dummy:/app --rm phpunit/phpunit index.php
