<?php
require dirname(__FILE__).'/../vendor/autoload.php';

$queue = new Phalcon\Queue\Beanstalk(
    array(
        'host' => '127.0.0.1',
        'port' => '11300'
    )
);

const DEFAULT_URL = 'https://pval-762a5.firebaseio.com/';
const DEFAULT_TOKEN = 'FCNtNVoEbEgeaEVYY3BS8XgtS1n2B3BBpZcYtVZx';
const DEFAULT_PATH = '/firebase/example';