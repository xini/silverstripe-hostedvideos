<?php
class HostedVideoVersion extends DataObject {
    
    private static $db = array(
        "Format" => "Varchar(10)",
        "Resolution" => "Int",
    );
    
    private static $has_one = array(
        "File" => "File",
    );
    
    private static $summary_fields = array(
        'File.Name' => 'File',
        'Format' => 'Format',
        'Resolution' => 'Resolution',
    );
    
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        
        //resolutions
        $resConfig = Config::inst()->get('HostedVideoExtension', 'resolutions');
        $resolutions = array();
        foreach ($resConfig as $key => $value) {
            $resolutions[$value] = $key . ' (' . $value . 'p)';
        }
        
        $fields->replaceField(
            'Resolution',
            OptionsetField::create(
                "Resolution",
                "Video Resolution",
                $resolutions,
                array_shift($resolutions)
            )
        );
        
        // formats
        $formatConfig = Config::inst()->get('HostedVideoExtension', 'formats');
        $filetypes = array_keys($formatConfig);
        $formats = array();
        foreach ($formatConfig as $item) {
            $formats[$item['type']] = $item['label'];
        }
        
        $fields->removeByName('Format');
        
        $fields->replaceField(
            'File',
            ChunkedUploadField::create(
                "File",
                "Video File"
            )->setFolderName('videos')
            ->setAllowedExtensions($filetypes)
            ->setDescription("The following video formats are allowed:<br /> - ".implode('<br /> - ', array_values($formats)))
        );
        
        return $fields;
    }
    
    protected function onBeforeWrite() {
        parent::onBeforeWrite();
        
        if ($file = $this->File()) {
            $config = Config::inst()->get('HostedVideoExtension', 'formats');
            $extension = $file->getExtension();
            if (isset($config[$extension])) {
                $this->Format = $config[$extension]['type'];
            } else {
                $this->Format = '';
            }
        }
        
    }
    
    public function getResolutionLabel() {
        $resConfig = Config::inst()->get('HostedVideoExtension', 'resolutions');
        if ($this->Resolution) {
            foreach ($resConfig as $key => $value) {
                if ($this->Resolution == intval($value, 10)) {
                    return strtolower($key);
                }
            }
        }
        return null;
    }
}