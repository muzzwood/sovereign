<?php
class SovereignAfricanArtworkCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'galleryAfricanImages';
    public $languageTopics = array('sovereign:default');
    public $objectType = 'sovereign';
    /** @var galleryAfricanImages $object */
    public $object;

    /** @var modMediaSource $source */
    public $source;


    public function checkPermissions() {
        return $this->modx->hasPermission('file_upload');
    }

    public function getLanguageTopics() {
        $langs = parent::getLanguageTopics();
        $langs[] = 'sovereign';
        return $langs;
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'source' => 1,
            'gallery' => false,
        ));
        if (!$this->getProperty('gallery')) return $this->modx->lexicon('file_folder_err_ns');
        return parent::initialize();
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $success = $this->source->uploadObjectsToContainer($this->getProperty('gallery'),$_FILES);

        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->modx->error->addField($k,$msg);
            }
            return $this->failure($msg);
        }
        return parent::process();
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$this->getProperty('source'));
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }

}
return 'SovereignAfricanArtworkCreateProcessor';

