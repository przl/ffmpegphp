<?php 

//only get files with certain extensions
$files = array_values(array_filter(scandir('queue'), function($file){
	return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|SWF|VOB|WMV|MKV)$/i', $file);
}));

$names = json_decode(@file_get_contents('completed/names.json'), true) ?: array();

if (count($files)) {
	$output_hash_name = md5($files[0].time()) . '.mp4';
	$input_hash_name = md5($files[0].time()) . '.' . pathinfo($files[0], PATHINFO_EXTENSION);
	$names['output'][$output_hash_name] = pathinfo($files[0], PATHINFO_FILENAME) . '.mp4';
	$names['input'][$input_hash_name] = $files[0];
	file_put_contents('completed/names.json', json_encode($names));
	rename( 'queue/' .$files[0], 'processing/input_processing/' . $input_hash_name);
	echo exec('ffmpeg -i processing/input_processing/' . $input_hash_name . ' -filter:v scale="1280:trunc(ow/a/2)*2" -c:v libx264 -loglevel error -y processing/output_processing/"' . $output_hash_name . '" >> logs/ffmpeg-errors.txt 2>&1 && rm processing/input_processing/' . $input_hash_name . ' && mv processing/output_processing/' . $output_hash_name . ' completed/' . $output_hash_name . ' || (rm processing/output_processing/' . $output_hash_name . ' && mv processing/input_processing/' . $input_hash_name . ' incomplete/' . $input_hash_name . ')');
}

?>