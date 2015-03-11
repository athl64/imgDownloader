#ImgDownloader

*Class to download files by HTTP/FTP (without any password-protections) from remote server to local directory.*

Usage:
    Put file named 'files.txt' into folder with main script 'downloader.php',
    from command line (Bash/CMD) type '~: php downloader.php'.
OR
    Open file 'downloader.php', edit path to file with files list from 'files.list' to
    your's, save changes and type '~: php downloader.php' as in previous case.

'files.list' format:
    Each line of file must contain a valid URL to file, for example:

    ---------file content begin------------
    http://somehost.net/dir/file.jpg
    http://someElseHost.net/dir/file.png
    ---------file content end -------------

Allowed files types:
    By default, allowed are 'jpeg', 'png', 'gif'
    You can modify allowed files types by changing array '$allowedMimeTypes' 
    in 'base/imgDownloader.php' using valid types from 
    http://www.iana.org/assignments/media-types/media-types.xhtml

Download folder:
    By default, folder named 'downloads/' will be created and will contain downloaded files.
    You can change it by editing '$prefix' in 'base/ingDownloader.php'.

Project content:
    +--imgDownloader
    |  +-base
    |    +-imgDownloader.php
    |    +-imgDownloaderException.php
    |  +-downloader.php

**For demonstration purposes**

Project contains file 'files.list' with few file pathes on my server:
    File 'files.list' contains 4 pathes:
        - http://dvixi.in.ua/tstImages/pic1.jpg - exists on server
        - http://dvixi.in.ua/tstImages/pic2.png - exists on server
        - http://dvixi.in.ua/tstImages/pic3.xcf - exists on server
        - http://dvixi.in.ua/tstImages/pic4.jpg - doesn't exist

    There are 3 files in 'http:/dvixi.in.ua/tstImages/' folder:
        1. pic1.jpg
        2. pic2.png
        3. pic3.xcf
