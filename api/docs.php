<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Conversion Docs</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
	<h1>FFMPEG MP4 Converter</h1>
		<table class="table"> 
			<tr>
				<th>URL</th>
				<th>Data In</th>
				<th>Success!</th>
				<th>Fail</th>
			</tr>
			<tr>
				<td rowspan="3">/api/file_upload.php</td>
				<td>email {GET Param, text e.g. example@ukfast.co.uk}</td>
				<td rowspan="3"> <span style='font-weight: bold'>200</span> An email will be sent the user with a link to download the completed file</td>
				<td rowspan="3" style='font-weight: bold'>500</td>
			</tr>
			<tr><td>resolution {GET Param, int, should just be the desired width	 e.g. 1080}</td></tr>
			<tr><td>file {3G2,3GP,ASF,AVI,FLV,M4V,MOV,MP4,MPG,RM,SRT,VOB,WMV,MKV}</td></tr>

			<tr>
				<td>/api/get_info.php</td>
				<td style='font-style: italic;'>None</td>
				<td>
				<span style='font-weight: bold'>200</span>
				<pre>JSON 
{
  "files": [
    {
      "example_hashed_name1.mp4": {
        "size": "1.22 MB",
        "created_at": "2016-02-29 11:06:56"
      }
    }
  ],
  "ongoing_files": [
    {
      "example_hashed_name2.mp4": {
        "size": "1.22 MB",
        "created_at": "2016-02-29 11:06:56"
      }
    }
  ],
  "failures": [
    {
      "example_hashed_name3.mp4": {
        "size": "1.22 MB",
        "created_at": "2016-02-29 11:06:56"
      }
    }
  ],
  "names": {
    "output": {
      "example_hashed_name1.mp4": "original_name1.mp4",
      "example_hashed_name2.mp4": "original_name2.mp4"
    },
    "input": {
      "example_hashed_name1.mp4": "original_name1.mp4",
      "example_hashed_name2.mp4": "original_name2.avi",
      "example_hashed_name3.mp4": "something.flv"
    }
  }
}
				</pre>
				</td>
				<td style="font-weight: bold">500</td>
			</tr>
		</table>
	</div>
</body>
</html>