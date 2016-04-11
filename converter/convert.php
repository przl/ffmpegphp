<?php

namespace ffmpegconverter\convert;

require '../config/config.php';

use ffmpegconverter\config\Config;

/**
 * FFMPEG converter!
 */
class FfmpegConverter
{

    const QUEUE_FOLDER     = '../queue';
    const COMPLETED_FOLDER = '../completed/';
    const NAMES_FILE       = '../completed/names.json';

    public function __construct($file, $resolution = null, $email = null)
    {
        $this->resolution = (int) $resolution;
        $this->email      = $email;
        $this->file       = $file;
        $this->names      = $this->getNames(static::NAMES_FILE);
    }

    public function filterFiles($directory)
    {
        return array_values(array_filter(scandir($directory), function ($file) {
            return preg_match('/^.*\.(3G2|3GP|ASF|AVI|FLV|M4V|MOV|MP4|MPG|RM|SRT|VOB|WMV|MKV)$/i', $file);
        }));
    }

    public function getNames($directory)
    {
        return json_decode(file_get_contents($directory), true) ?: array();
    }

    public function sendEmail($video_link)
    {
        if ($this->email) {
            return mail($this->email, 'Conversion Completed', 'Your video file has been converted, please find the new file at ' . Config::get_web_path() . '/completed/' . $video_link);
        }
    }

    public function convert()
    {

        if ($this->file) {

            $output_hash_name = md5($this->file . time()) . '.mp4';
            $input_hash_name  = md5($this->file . time()) . '.' . pathinfo($this->file, PATHINFO_EXTENSION);

            ob_end_clean();
            header("Connection: close");
            ignore_user_abort(true); // just to be safe
            ob_start();
            echo ($output_hash_name);
            $size = ob_get_length();
            header("Content-Length: $size");
            ob_end_flush(); // Strange behaviour, will not work
            flush(); // Unless both are called !
            rename('../queue/' . $this->file, '../processing/input_processing/' . $input_hash_name);

            //wait until there are less than 30 ffmpeg processes
            while ($this->getNumberOfConversionProcesses('ffmpeg') >= 10) {
                sleep(5);
            }
            //clean up!
            $this->cleanFolders();

            $this->sendEmail($output_hash_name);
            $this->names['output'][$output_hash_name] = pathinfo($this->file, PATHINFO_FILENAME) . '.mp4';
            $this->names['input'][$input_hash_name]   = $this->file;
            file_put_contents(static::NAMES_FILE, json_encode($this->names));

            exec('ffmpeg -i ../processing/input_processing/' . $input_hash_name . ' -filter:v scale="' . $this->resolution . ':trunc(ow/a/2)*2" -c:v libx264 -loglevel error -y ../processing/output_processing/"' . $output_hash_name . '" >> ../logs/ffmpeg-errors.txt 2>&1 && rm -f ../processing/input_processing/' . $input_hash_name . ' && mv ../processing/output_processing/' . $output_hash_name . ' ../completed/' . $output_hash_name . ' || (rm -f ../processing/output_processing/' . $output_hash_name . ' && mv ../processing/input_processing/' . $input_hash_name . ' ../incomplete/' . $input_hash_name . ')', $exec_output, $exec_return);

            echo "Completed Conversion";

            return true;

        }
        return false;
    }

    //
    public function getNumberOfConversionProcesses($command)
    {
        return exec('pgrep ffmpeg | wc -l');
    }

    //delete files in completed older than 30 days
    public function cleanFolders()
    {
        $files = glob(static::COMPLETED_FOLDER . '*.mp4');
        foreach ($files as $file) {
            if (is_file($file)) {
                $filemtime = filemtime($file);
                if (time() - $filemtime >= 60 * 60 * 24 * 10) {
                    unlink($file);
                }
            }
        }
    }

}
