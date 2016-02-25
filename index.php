<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mp4 Coverter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">     

<?php

const VIDEO_DIR = 'completed/';
const FAILED_DIR = 'incomplete/';
const ONGOING_DIR = 'processing/input_processing/';
const OUTGOING_DIR = 'processing/output_processing/';

//get completed files
$files = array_values(array_filter(scandir(VIDEO_DIR), function($file){
    return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|SWF|VOB|WMV|MKV)$/i', $file);
}));

//get ongoing conversions
$ongoing_files = array_values(array_filter(scandir(OUTGOING_DIR), function($file){
    return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|SWF|VOB|WMV|MKV)$/i', $file);
}));

//get failed conversions
$failures = array_values(array_filter(scandir(FAILED_DIR), function($failure){
    return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|SWF|VOB|WMV|MKV)$/i', $failure);
}));

$names = $names = json_decode(file_get_contents(VIDEO_DIR . 'names.json'), true);

?>


<h1>Completed Conversions</h1>

<table class="table">
    <tr>
        <th>Filename</th>
        <th>Size</th>
        <th>Time Created</th>
    </tr>
    <?php 

    foreach($files as $file){

        $original_name = $names['output'][$file];

        echo " 
                <tr>
                    <td><a href='". VIDEO_DIR . "$file'</a>$original_name</td>
                    <td> " . formatSizeUnits(filesize(VIDEO_DIR . $file)) . " </td>
                    <td> " . date ("Y-m-d-H:i:s", filemtime(VIDEO_DIR . $file)) . "</td>
                </tr>

            ";
    }
?>

</table>

<h1>Ongoing Conversions</h1>

<table class="table">
    <tr>
        <th>Filename</th>
        <th>Size</th>
        <th>Time Created</th>
    </tr>
    <?php 

    foreach($ongoing_files as $ongoing){

        $original_name = $names['output'][$ongoing];

        echo " 
                <tr>
                    <td>$original_name</td>
                    <td> " . formatSizeUnits(filesize(OUTGOING_DIR . $ongoing)) . "
                    <td> " . date ("Y-m-d-H:i:s", filemtime(OUTGOING_DIR . $ongoing)) . "</td>
                </tr>
            ";
    }
?>

</table>


<h1>Failed Conversions</h1>

<table class="table">
    <tr>
        <th>Original Name</th>
        <th>File Name</th>
        <th>Time Created</th>
    </tr>
    <?php 

    foreach($failures as $failure){

        $original_name = $names['input'][$failure];

        echo " 
                <tr>
                    <td>$original_name</td>
                    <td> $failure </td>
                    <td> " . date ("Y-m-d-H:i:s", filemtime(FAILED_DIR . $failure)) . "</td>
                </tr>

            ";
    }
?>

</table>

</div>
</body>
</html>

<?php 
// Snippet from PHP Share: http://www.phpshare.org

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
?>
