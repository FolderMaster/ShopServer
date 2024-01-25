<?php

namespace Control;

use \PDO;

const DataBaseConnection = new PDO(
    'mysql:host=localhost;dbname=shopdb',
    'root',
    '1056'
);
