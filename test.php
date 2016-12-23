<?php
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->delete(['file_count', 'handled_count', 'success_count', 'failure_count']);
    $redis->close();