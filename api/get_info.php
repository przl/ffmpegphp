<?php

namespace ffmpegconverter\api;

require_once 'apiInfo.php';

$info = new FfmpegApi('../completed/', '../incomplete/', '../processing/input_processing/', '../processing/output_processing/');

header('Content-Type: application/json');
echo $info->getInfo();
