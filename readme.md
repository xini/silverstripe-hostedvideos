# Self-hosted video integration (also supporting YouTube and Vimeo videos)

**This is an archived project and is no longer maintained. Please do not file issues or pull-requests against this repo. If you wish to continue to develop this code yourself, we recommend you fork it or contact us.**

This module allows adding locally hosted, YouTube or Vimeo videos to any object.

It uses 
* [ChunkedUploadField] (https://github.com/micschk/silverstripe-chunkeduploadfield) to allow uploads larger than the configured PHP limits
* [afterglow video player] (https://afterglowplayer.com/) for locally stored videos (which allows SD and HD versions of videos)
* native YouTube and Vimeo embedding for those videos (afterglow could be used here too, but then the videos wouldn't work without javascript)

When adding Vimeo and YouTube videos, you can add the video code or the whole URL. If URLs are given, the URLs are parsed the video codes extracted.

## Usage

Add the `HostedVideoExtension` to any page/dataobject you want to contain a video. This will add the fields for the object to contain one single video.

Insert `$HostedVideo` in your template, wherever you want your video to show. 

## Configuration

### video sources

By default all three video sources are enabled. You can disable any of them using the following config options:

```
HostedVideoExtension:
  disable_selfhosted: true
  disable_vimeo: true
  disable_youtube: true
```

### video resolutions

By default there are two video resultions configured for self hosted videos:

```
HostedVideoExtension:
  resolutions:
    SD: 480
    HD: 720
```

You can change these settings and add more resultions if you would like to give the user more options. 

### display size

You can specify the display size of the videos in your yml config:

```
HostedVideoExtension:
  display_size:
    width: 560
    height: 315
```

The default CSS treats all videos as having a 16:9 ratio (56.25% height).

### formats and media types

You can specify the media types allowed for your self hosted videos in the yml config:

```
HostedVideoExtension:
  formats:
    mp4:
      type: 'video/mp4'
      label: 'MP4 (H.264 with AAC or MP3 audio)'
    webm:
      type: 'video/webm'
      label: 'WebM (VP8/VP9 with Vorbis or Opus audio)'
    ogg:
      type: 'video/ogg'
      label: 'Ogg (Theora with Vorbis audio)'
```

Please make sure the configured media types are also defined on your server (.htaccess for Apache):

```
AddType video/mp4 .mp4
AddType video/webm .webm
AddType video/ogg .ogg
```
