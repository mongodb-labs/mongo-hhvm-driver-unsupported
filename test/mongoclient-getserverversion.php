<?php

$mc = new MongoClient();
printf("Server version: %s\n", $mc->getServerVersion());
