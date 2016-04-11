<?php

namespace ffmpegconverter\api;

require '../converter/convert.php';

use ffmpegconverter\convert\FfmpegConverter;

//check if resolution is a number
if (!is_numeric($_GET['resolution'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo 'Please set resolution to an integer value';
    return;
}

//check file is of a certain format then process
if (preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|VOB|WMV|MKV)$/i', $_GET['file_name'])) {

    if (strlen($_GET['file_name']) > 0 && file_exists("../queue/" . $_GET['file_name'])) {

        $converter = new FfmpegConverter('../queue/' . $_GET['file_name'], $_GET['resolution'], $_GET['email']);

        $converter->convert();
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        echo 'File does not exist on server';
        return;
    }
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo 'Please upload a file in one of the follow formats: 3G2,3GP,ASF,AVI,FLV,M4V,MOV,MP4,MPG,RM,SRT,SWF,VOB,WMV,MKV';
    return;
}
;

return;
