# in home directory, make a directory to put the source code
mkdir ~/ffmpeg_sources

#get x264
cd ~/ffmpeg_sources
git clone --depth 1 git://git.videolan.org/x264
cd x264
PKG_CONFIG_PATH="$HOME/ffmpeg_build/lib/pkgconfig" ./configure --prefix="$HOME/ffmpeg_build" --bindir="$HOME/bin" --enable-static
make
make install
make distclean

#setup ffmpeg
cd ~/ffmpeg_sources
git clone http://source.ffmpeg.org/git/ffmpeg.git
cd ffmpeg
PKG_CONFIG_PATH="$HOME/ffmpeg_build/lib/pkgconfig" ./configure --prefix="$HOME/ffmpeg_build" --extra-cflags="-I$HOME/ffmpeg_build/include" --extra-ldflags="-L$HOME/ffmpeg_build/lib" --bindir="$HOME/bin" --pkg-config-flags="--static" --enable-gpl --enable-libx264
make
make install
make distclean
hash -r

#test is installed (should give a list of options)
ffpmeg

#add extension to php.ini
extension=ffmpeg.so

#create folders in the location of your page
mkdir -m 777 completed && mkdir -m 777 incomplete && mkdir -m 777 logs && mkdir -m 777 processing && mkdir -m 777 queue && mkdir -m 777 processing/input_processing && mkdir -m 777 processing/output_processing

#instructions..
put a file in the queue folder, run convert.php through apache (browser). If a file is successfully processed, the output will be found in the completed folder.
If a conversion fails for any reason, the original file will be moved to incomplete folder.
The index file will show completed conversions / ongoing conversions & incomplete (erroneous) conversions.

#CRON
for a cron task, put a hit to /convert.php every 5 minutes or so (depending on the size of the uploaded files whether your server sucks or not)



#Todo
- implement a queueing system per user (this process can be quite resource intensive and new ffmpeg processes can start killing off existing ones. At the moment this is handled, but it shouldn't really be a thing.
- uploader tool for user interaction