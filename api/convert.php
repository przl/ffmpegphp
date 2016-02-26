<?php

namespace ffmpegconverter\api;

require '../converter/convert.php';

use ffmpegconverter\convert\FfmpegConverter;

$converter = new FfmpegConverter;

$converter->convert();
