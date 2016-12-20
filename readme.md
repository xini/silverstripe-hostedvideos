# Self-hosted video integration (also supporting YouTube and Vimeo videos)

## Usage

Add the `HostedVideoExtension` to any page/dataobject you want to contain a video. This will add the fields for the object to contain one single video.

Insert `$HostedVideo` in your template, wherever you want your video to show. 

## Configuration

### default size

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