<?php

namespace ffmpegconverter\api;

require_once '../converter/convert.php';

use ffmpegconverter\convert\FfmpegConverter;

/**
 * Gets info about the files on the ffmpeg conversion server
 */
class FfmpegApi extends FfmpegConverter
{

    public function __construct($video_dir, $failed_dir, $ongoing_dir, $outgoing_dir)
    {
        $this->video_dir    = $video_dir;
        $this->failed_dir   = $failed_dir;
        $this->ongoing_dir  = $ongoing_dir;
        $this->outgoing_dir = $outgoing_dir;
    }

    public function attachFileInfo($files, $directory)
    {
        $new_files = array();
        foreach ($files as $file) {
            $new_files[] = array('name' => $file, 'size' => $this->formatSizeUnits(filesize($directory . $file)), 'created_at' => date('Y-m-d H:i:s', filemtime($directory . $file)));
        }
        return $new_files;
    }

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function getInfo()
    {
        //get completed conversions
        $info['files'] = $this->attachFileInfo($this->filterFiles($this->video_dir), $this->video_dir);
        //get ongoing conversions
        $info['ongoing_files'] = $this->attachFileInfo($this->filterFiles($this->outgoing_dir), $this->outgoing_dir);
        //get failed conversions
        $info['failures'] = $this->attachFileInfo($this->filterFiles($this->failed_dir), $this->failed_dir);
        //get names for files
        $info['names'] = json_decode(file_get_contents($this->video_dir . 'names.json'), true);
        return json_encode($info);
    }
}
