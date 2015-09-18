<?php

/**
 * Description of ItemLocator
 *
 * @author Markus Sommerfeld <markus@simpleasthat.de>
 */
class ItemLocator {

    /**
     * 
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     *
     * @var type 
     */
    private $sources = array();

    /**
     *
     * @var type
     */
    private $currentSource = null;

    
    /**
     * List of allowed file (image) types
     * @var array
     */
    private $allowedFileTypes = array('png', 'jpg', 'gif');

    /**
     * list of all items
     * @var array
     */
    private $items = array();

    /**
     *
     * @param mixed $sourceFolder
     * @param array $allowedFilesTypes
     * @throws InvalidArgumentException
     */
    public function __construct( $sourceFolder = null, $allowedFilesTypes = array() ) {


        if(!$sourceFolder){
            throw new InvalidArgumentException('NO SOURCE FOLDER GIVEN!');
        }

        if(!empty($allowedFilesTypes) && !is_array($allowedFilesTypes)){
            throw new InvalidArgumentException("PARAMETER 2 SHOULD BE OF TYPE ARRAY");
        }

        $this->sources          = $sourceFolder;
        $this->allowedFileTypes = (!empty($allowedFilesTypes)) ? $allowedFilesTypes : $this->allowedFileTypes;

        if(!is_array($this->sources)){
            $this->sources = array($this->sources);
        }
    }

    /**
     * returns a list of defined sources
     * @return array
     */
    public function getSources(){
        return $this->sources;
    }

    /**
     * returns a list of all usable items
     * @return array
     */
    public function getItems(){

        if(empty($this->items)){
            $this->findItems();
        }

        return $this->items;
    }

    /**
     *
     * @return array
     * @throws InvalidArgumentException
     */
    private function findItems(){
        foreach($this->getSources() as $souceFolder){
            if(!file_exists($souceFolder) || !is_dir($souceFolder)){
                throw new InvalidArgumentException(sprintf('NOT A DIRECTORY "%s"', $souceFolder));
            }

            // remove trailing slash
            $souceFolder = rtrim($souceFolder, "/");
            
            $this->currentSource = $souceFolder;
            $this->prepareItemList($souceFolder);

            // remove currentSource
            $this->currentSource = null;
        }

        $this->orderItems();
        return $this->items;
    }


    /**
     *
     * @param string $folder
     * @return void
     */
    private function prepareItemList( $folder ) {

        foreach (scandir($folder.self::DS) as $item) {

            if (preg_match("/^\./", $item)) {
                continue;
            }

            $path = $folder .self::DS. $item;

            if (is_file($path) && $this->isFileAllowed($path)) {
                $key = $this->prepareItemKey($path);
                $this->items[$key] = $path;
            }

            // Include sub folders
            elseif (is_dir($path)) {
                $this->prepareItemList($path);
            }
        }
    }


    /**
     * creates a save css class name
     * @param string $item
     * @return string
     */
    private function prepareItemKey($item) {
        $item = str_replace($this->currentSource, '', $item);
        $item = substr($item, 0, strrpos($item, "."));
        $item = implode(".", explode(self::DS, $item));
        $item = preg_replace("/[^a-z0-9_\.]+/i", "_", $item);
        return strtolower($item);
    }
    
    /**
     * orders the items
     * @return void
     */
    private function orderItems() {
        $newOrder = array();
        foreach ($this->items as $key => $path) {
            $newOrder[count(explode(".", trim($key, ".")))][$key] = $path;
        }

        ksort($newOrder);
        $this->items = array();

        foreach ($newOrder as $items) {
            ksort($items);
            $this->items = array_merge($this->items, $items);
        }
    }


    /**
     * check is file allowed
     * @param type $file
     * @return boolean
     */
    private function isFileAllowed( $file ) {
        $file = basename($file);
        $fileSuffix = substr($file, strrpos($file, ".") + 1);
        return (in_array($fileSuffix, $this->allowedFileTypes));
    }
}
