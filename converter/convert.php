<?php

namespace ffmpegconverter\convert;

/**
 * FFMPEG converter!
 */
class FfmpegConverter
{
    const QUEUE_FOLDER = '../queue';
    const NAMES_FILE   = '../completed/names.json';

    public function __construct($resolution = null, $email = null)
    {
        $this->resolution = $resolution;
        $this->email      = $email;
        $this->files      = $this->filterFiles(static::QUEUE_FOLDER);
        $this->names      = $this->getNames(static::NAMES_FILE);
    }

    public function filterFiles($directory)
    {
        return array_values(array_filter(scandir($directory), function ($file) {
            return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|SWF|VOB|WMV|MKV)$/i', $file);
        }));
    }

    public function getNames($directory)
    {
        return json_decode(file_get_contents($directory), true) ?: array();
    }

    public function convert()
    {
        if (count($this->files)) {
            $output_hash_name                         = md5($this->files[0] . time()) . '.mp4';
            $input_hash_name                          = md5($this->files[0] . time()) . '.' . pathinfo($this->files[0], PATHINFO_EXTENSION);
            $this->names['output'][$output_hash_name] = pathinfo($this->files[0], PATHINFO_FILENAME) . '.mp4';
            $this->names['input'][$input_hash_name]   = $this->files[0];
            file_put_contents('../completed/names.json', json_encode($this->names));
            rename('../queue/' . $this->files[0], '../processing/input_processing/' . $input_hash_name);
            echo exec('ffmpeg -i ../processing/input_processing/' . $input_hash_name . ' -filter:v scale="1280:trunc(ow/a/2)*2" -c:v libx264 -loglevel error -y ../processing/output_processing/"' . $output_hash_name . '" >> ../logs/ffmpeg-errors.txt 2>&1 && rm ../processing/input_processing/' . $input_hash_name . ' && mv ../processing/output_processing/' . $output_hash_name . ' ../completed/' . $output_hash_name . ' || (rm ../processing/output_processing/' . $output_hash_name . ' && mv ../processing/input_processing/' . $input_hash_name . ' ../incomplete/' . $input_hash_name . ')');
            return true;
        }
        return false;
    }
}
