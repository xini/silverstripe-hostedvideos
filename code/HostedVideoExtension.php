<?php
class HostedVideoExtension extends DataExtension {
    
    private static $db = array(
        'VideoSource' => "Enum('Self-Hosted,YouTube,Vimeo')",
        'YoutubeCode' => 'Varchar(50)',
        'VimeoCode' => 'Varchar(50)',
    );
    
    private static $many_many = array(
        'VideoVersions' => "HostedVideoVersion",
    );
    
    public function updateCMSFields(FieldList $fields) {
        
        $fields->removeByName('VideoSource');
        $fields->removeByName('YoutubeCode');
        $fields->removeByName('VimeoCode');
        $fields->removeByName('VideoVersions');
        
        $sources = array();
        if (!Config::inst()->get('HostedVideoExtension', 'disable_youtube')) {
            $sources['YouTube'] = 'YouTube';
        }
        if (!Config::inst()->get('HostedVideoExtension', 'disable_vimeo')) {
            $sources['Vimeo'] = 'Vimeo';
        }
        if (!Config::inst()->get('HostedVideoExtension', 'disable_selfhosted')) {
            $sources['Self-Hosted'] = 'Self-Hosted';
        }
        
        $fields->addFieldsToTab(
            'Root.Video', 
            array(
                DropdownField::create(
                    'VideoSource',
                    'Video Source',
                    $sources
                ),
                $videoYoutubeWrapper = DisplayLogicWrapper::create(
                    TextField::create('YoutubeCode', 'Youtube Code')
                        ->setRightTitle('You can enter the full YouTube URL of the video and the code will be extracted.')
                )->addExtraClass('field'),
                $videoVimeoWrapper = DisplayLogicWrapper::create(
                    TextField::create('VimeoCode', 'Vimeo Code')
                        ->setRightTitle('You can enter the full Vimeo URL of the video and the code will be extracted.')
                )->addExtraClass('field'),
                $videoVersionsWrapper = DisplayLogicWrapper::create(
                    GridField::create(
                        'VideoVersions',
                        'Video Versions',
                        $this->owner->VideoVersions(),
                        GridFieldConfig_RecordEditor::create()
                    )
                )->addExtraClass('field')
            )
        );
        $videoYoutubeWrapper->displayIf("VideoSource")->isEqualTo("YouTube");
        $videoVimeoWrapper->displayIf("VideoSource")->isEqualTo("Vimeo");
        $videoVersionsWrapper->displayIf("VideoSource")->isEqualTo("Self-Hosted");
        
    }
    
    public function onBeforeWrite() {
        // clean up data
        if ($this->owner->VideoSource == "YouTube") {
            $this->owner->VimeoCode = "";
        } else if ($this->owner->VideoSource == "Vimeo") {
            $this->owner->YoutubeCode = "";
        } else {
            $this->owner->YoutubeCode = "";
            $this->owner->VimeoCode = "";
        }
        if ($this->owner->VideoSource == "YouTube" || $this->owner->VideoSource == "Vimeo") {
            if (($videos = $this->owner->VideoVersions()) && $videos->exists()) {
                foreach ($videos as $video) {
                    $video->delete();
                }
            }
        }
        // extract youtube code
        if (!preg_match('/^[\w-]{11}$/', $this->owner->YoutubeCode)) {
            $this->owner->YoutubeCode = HostedVideoExtension::YoutubeIDFromUrl($this->owner->YoutubeCode);
        }
        // extract vimeo code
        if (!preg_match('/^[0-9]+$/', $this->owner->VimeoCode)) {
            $this->owner->VimeoCode = HostedVideoExtension::VimeoIDFromUrl($this->owner->VimeoCode);
        }
    }
    
    public function HostedVideo() {
        Requirements::css('hostedvideos/css/hostedvideos.css');
        Requirements::javascript('hostedvideos/vendor/afterglow/afterglow.min.js');
        
        $sizes = Config::inst()->get('HostedVideoExtension', 'display_size');
        return $this->owner->customise(new ArrayData(array(
            'Width' => $sizes['width'],
            'Height' => $sizes['height'],
        )))->renderWith("HostedVideo");
        
    }
    
    public function SortedVideoVersions() {
        return $this->owner->VideoVersions()->sort(array(
            "Resolution" => "ASC",
        ));
    }
    
    public static function YoutubeIDFromUrl($url) {
        if (preg_match('/^[\w-]{11}$/', $url)) {
            return $url;
        }
        $pattern =
        '%^# Match any youtube URL
    			(?:https?://)?  # Optional scheme. Either http or https
    			(?:www\.)?	    # Optional www subdomain
    			(?:			    # Group host alternatives
    			  youtu\.be/	# Either youtu.be,
    			| youtube\.com  # or youtube.com
    			  (?:		    # Group path alternatives
    				/embed/	    # Either /embed/
    			  | /v/		    # or /v/
    			  | /watch\?v=  # or /watch\?v=
    			  )			    # End path alternatives.
    			)			    # End host alternatives.
    			([\w-]{11})     # Allow 10-12 for 11 char youtube id.
                .*              # other parameters
    			$%x'
            ;
            $result = preg_match($pattern, $url, $matches);
            if (false !== $result && isset($matches[1])) {
                return $matches[1];
            }
            return false;
    }
    
    public static function VimeoIDFromUrl($url) {
        if (preg_match('/^[0-9]+$/', $url)) {
            return $url;
        }
        $pattern = '/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/';
        $result = preg_match($pattern, $url, $matches);
        if (false !== $result && isset($matches[3])) {
            return $matches[3];
        }
        return false;
    }
}