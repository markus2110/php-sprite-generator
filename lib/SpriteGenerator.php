<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sprite
 *
 * @author Markus
 */


defined('DS') || define('DS', DIRECTORY_SEPARATOR);

class Sprite {
  
  
  /**
   * Image Source Type
   * ZIP or PATH
   * 
   * @var string
   */
  private $sourceType = "PATH";
  
  /**
   * Path to images
   * @var string
   */
  private $sourcePath = null;  
  
  
  /**
   * save path
   * @var string
   */
  private $savePath = null;    
  
  /**
   *
   * @var resource an image resource identifier
   */
  private $spriteImage = null;
  
  /**
   * CSS class prefix
   * @var string
   */
  private $cssPrefix    = "MySprite";  
  
  /**
   * 
   * @var string
   */
  private $cssFormat = "%s {background:url(%s) %dpx %dpx no-repeat;height:%dpx;width:%dpx;}";
  
  /**
   * Set to true, to include images from subdirectories to sprite
   * @var boolean 
   */
  private $includeSubDir = false;
  
  
  /**
   * List of images
   * @var array 
   */
  private $spriteItems = array();
  
  
  /**
   * The max sprite width
   * @var integer
   */
  private $spriteMaxWidth = 300;    
  
  /**
   * The offset between images
   * @var integer
   */
  private $spriteImageOffset = 2;  
  
  
  /**
   * List of allowed file (image) types
   * @var array 
   */
  private $allowedFileTypes = array('png','jpg', 'gif');  
  
  
  /**
   * filtertype can be one of the following:
   *
   *  IMG_FILTER_NEGATE: Reverses all colors of the image.
   *  IMG_FILTER_GRAYSCALE: Converts the image into grayscale.
   *  IMG_FILTER_BRIGHTNESS: Changes the brightness of the image. Use arg1 to set the level of brightness.
   *  IMG_FILTER_CONTRAST: Changes the contrast of the image. Use arg1 to set the level of contrast.
   *  IMG_FILTER_COLORIZE: Like IMG_FILTER_GRAYSCALE, except you can specify the color. Use arg1, arg2 and arg3 in the form of red, green, blue and arg4 for the alpha channel. The range for each color is 0 to 255.
   *  IMG_FILTER_EDGEDETECT: Uses edge detection to highlight the edges in the image.
   *  IMG_FILTER_EMBOSS: Embosses the image.
   *  IMG_FILTER_GAUSSIAN_BLUR: Blurs the image using the Gaussian method.
   *  IMG_FILTER_SELECTIVE_BLUR: Blurs the image.
   *  IMG_FILTER_MEAN_REMOVAL: Uses mean removal to achieve a "sketchy" effect.
   *  IMG_FILTER_SMOOTH: Makes the image smoother. Use arg1 to set the level of smoothness.
   *  IMG_FILTER_PIXELATE: Applies pixelation effect to the image, use arg1 to set the block size and arg2 to set the pixelation effect mode.
   *
   *  #arg1
   *    IMG_FILTER_BRIGHTNESS: Brightness level.
   *    IMG_FILTER_CONTRAST: Contrast level.
   *    IMG_FILTER_COLORIZE: Value of red component.
   *    IMG_FILTER_SMOOTH: Smoothness level.
   *    IMG_FILTER_PIXELATE: Block size in pixels.
   *
   *  #arg2
   *    IMG_FILTER_COLORIZE: Value of green component.
   *    IMG_FILTER_PIXELATE: Whether to use advanced pixelation effect or not (defaults to FALSE).
   *
   *  #arg3
   *    IMG_FILTER_COLORIZE: Value of blue component.
   *
   *  #arg4
   *    IMG_FILTER_COLORIZE: Alpha channel, A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely transparent. 
   */
  private $itemFilter = array(
  // StyleName => Class Suddix|Filter Args1|Arg2|Arg3|Arg4
    'NO-FILTER'       => '',
    'GRAYSCALE'       => 'grey',
    'COLORIZE'        => 'colorize|0|0|0|64',
    #'GAUSSIAN_BLUR'   => 'blur',
    #'NEGATE'          => 'negate',

    #'BRIGHTNESS'      => 'brightness|75',
    #'CONTRAST'        => 'contrast|-10',
    
    #'EDGEDETECT'      => 'edge',
    #'EMBOSS'          => 'embosses',
    #'SELECTIVE_BLUR'  => 'sblur',
    #'MEAN_REMOVAL'    => 'sketchy',
    #'SMOOTH'          => 'smooth|3',
    #'PIXELATE'        => 'pixel|5|5'
  );  
  
  
  
  

  /********************************
   * PUBLIC METHODS
   ********************************/  
  
  public function __construct() {}
  
  
  
  
  public function generate(){
    
    $this->findSpriteItmes($this->getSourcePath());
    $this->prepareItemPositions();
    $spriteSize = $this->calculateSpriteSize();

    $this->spriteImage = @imagecreatetruecolor($spriteSize['width'],$spriteSize['height']);
    if($this->spriteImage){
      imagealphablending($this->spriteImage, false);
      imagesavealpha($this->spriteImage, true);
      // transparent
      $trans_color = imagecolorallocatealpha($this->spriteImage, 255, 255, 255, 127);
      imagefill($this->spriteImage, 0, 0, $trans_color);      
    }
    else{
      return false;
    }
    
    foreach($this->spriteItems as $cssName => $itemProp){
      $this->addImageToSprite($itemProp);
    }
    
    file_put_contents($this->getSavePath().$this->getCssPrefix().'.png', $this->getSpriteImageSource());
    file_put_contents($this->getSavePath().$this->getCssPrefix().'.css', $this->getCssData());
    
  }
  
  /**
   * 
   * @return type
   */
  public function getSpriteImageSource(){
    ob_start();
    imagepng($this->spriteImage);
    $imageData = ob_get_contents();
    ob_end_clean();    
    return $imageData;
  }

  
  /**
   * 
   * @return string
   */
  public function getCssData(){
    #$cssFileName = $this->destPath.$this->cssPrefix.".css";
    
    $cssString = null;
    foreach($this->spriteItems as $cssName => $itemProp){
      $cssData = array(
          'cssName' => $cssName,
          'url'     => $this->getCssPrefix().'.png',
          'h'       => -$itemProp['hPosStart'],
          'v'       => -$itemProp['vPosStart'],
          'height'  => $itemProp['height'],
          'width'   => $itemProp['width'],
      );
      $cssString .= vsprintf($this->getCssFormat(),$cssData)."\n";
    }
    
    return $cssString;
    
//    if(file_put_contents($cssFileName, $this->cssString)){
//      chmod($cssFileName, 0777);
//      return true;
//    }else{
//      die('Could not create CSS File :' . $cssFileName);
//    }    
  }   
  
  
  private function addImageToSprite($itemProp){

    switch($itemProp['type']){
      case 'PNG' : 
        $itemSource = imagecreatefrompng($itemProp['file']); 
        break;

      case 'GIF' : 
        $itemSource = imagecreatefromgif($itemProp['file']); 
        break;   

      case 'JPG' : 
      case 'JPEG' : 
        $itemSource = imagecreatefromjpeg($itemProp['file']); 
        break;         

      default :
        $itemSource = false;  
    }
    
    if($itemSource){
      
      if(isset($itemProp['filter']) && $itemProp['filter']=='NO-FILTER'){
        imagecopy ( $this->spriteImage , $itemSource , $itemProp['hPosStart'] , $itemProp['vPosStart'] , 0 , 0 , $itemProp['width'], $itemProp['height'] );        
      }
      elseif(isset($itemProp['filter'])){
        
        $FILTER = 'IMG_FILTER_'.$itemProp['filter'];
        
        $filterProp = explode("|",$this->itemFilter[$itemProp['filter']]);

        $params = array();
        $params[] = $itemSource;
        $params[] = constant($FILTER);
        
        if(isset($filterProp[1]))
          $params[] = $filterProp[1];
        if(isset($filterProp[2]))
          $params[] = $filterProp[2];
        if(isset($filterProp[3]))
          $params[] = $filterProp[3];
        if(isset($filterProp[4]))
          $params[] = $filterProp[4];
        
        imagealphablending($itemSource, false);
        imagesavealpha($itemSource, true);        
        call_user_func_array('imagefilter',$params);
        #imagefilter($itemSource,constant($FILTER), implode(",",$args));
        imagecopy ( $this->spriteImage , $itemSource , $itemProp['hPosStart'] , $itemProp['vPosStart'] , 0 , 0 , $itemProp['width'], $itemProp['height'] );                
      }
      imagedestroy($itemSource);  
    }    
  }  
  
  
 
  
  /**
   * 
   * @param string $folder
   * @return void
   */
  private function findSpriteItmes($folder=false){
    foreach(scandir($folder) as $index => $item){
      if(!preg_match("/^\./", $item)){
        
        if(is_file($folder.$item) && $this->isFileAllowed($folder.$item)){
          $this->spriteItems[$this->saveCssName($folder.$item)] = $folder.$item;
        }
        
        // Include sub folders
        elseif(is_dir($folder.$item) && $this->includeSubDir){
          $this->findSpriteItmes($folder.$item.DS);
        }
      }
    }
  }
  
  
  private function prepareItemPositions(){
    
    $itemList     = array();
    $widthLeft    = $this->getSpriteMaxWidth();
    $verticalPos  = 0;
    $horizonPos   = 0;
    $maxHeight    = 0;
    
    
    // iterate over the filters
    foreach($this->itemFilter as $filterSuffix => $cssSuffix){
      // iterate of the itemList
      foreach($this->spriteItems as $cssName => $file){
        $imageMetaData = getimagesize($file);

        $imageWidth     = $imageMetaData[0];
        $imageHeight    = $imageMetaData[1];
        $imageMimeType  = $imageMetaData['mime'];
        $imageType      = strtoupper(substr($file, strrpos($file, ".")+1));
        $offset         = $imageWidth+$this->getSpriteImageOffset();

        // Check is enough space left for the next image
        if(($widthLeft-$offset) > 0){
          $widthLeft = $widthLeft-$offset;
        }
        // not enought space, we calculate the next row position, including the last max. image + the offset
        else{
          $verticalPos = $verticalPos + $maxHeight + $this->getSpriteImageOffset();
          $widthLeft  = $this->getSpriteMaxWidth();
          $horizonPos = 0;
          $maxHeight  = 0;
        }

        $metaArray = array(
          'file'      => $file,
          'width'     => $imageWidth,
          'height'    => $imageHeight,
          'mimeType'  => $imageMimeType,
          'type'      => $imageType,
          'vPosStart' => $verticalPos,
          'vPosEnd'   => $verticalPos+$imageHeight,
          'hPosStart' => $horizonPos,
          'hPosEnd'   => $horizonPos+$imageWidth,
          'filter'    => $filterSuffix
        ); 

        if(!empty($cssSuffix)){
          $suffix = explode("|", $cssSuffix);
          $itemList[$cssName.'.'.$suffix[0]] = $metaArray;
        }else{
          $itemList[$cssName] = $metaArray;
        }

        // is current image higher as the current maxHight ?
        if($imageHeight>$maxHeight) $maxHeight = $imageHeight;      

        // next image pos
        $horizonPos = $horizonPos+$offset;        
      }
    }
    
    $this->spriteItems = $itemList;
  }   
  
  
  /**
   * creates a save css class name
   * @param string $item
   * @return string
   */
  private function saveCssName($item){
    $item = str_replace($this->getSourcePath(), '', $item);
    $item = substr($item, 0, strrpos($item, "."));
    $item = implode(".", explode(DS, $item));
    $item = ".".ltrim($this->getCssPrefix(), ".#").".".strtolower($item);
    return preg_replace("/[^a-z0-9_\.]+/i", "_", $item);
  }  
  
  
  /**
   * check is file allowed
   * @param type $file
   * @return boolean
   */
  private function isFileAllowed($file){
    $file = basename($file);
    $fileSuffix = substr($file, strrpos($file, ".")+1);
    return (in_array($fileSuffix, $this->allowedFileTypes));
  }
  
  
  private function calculateSpriteSize(){
    $maxWidth   = 0;
    $maxHeight  = 0;
    foreach($this->spriteItems as $itemProp){
      if($itemProp['hPosEnd']>$maxWidth)  $maxWidth   = $itemProp['hPosEnd'];
      if($itemProp['vPosEnd']>$maxHeight) $maxHeight  = $itemProp['vPosEnd'];
    }
    return array('width'=>$maxWidth,'height'=>$maxHeight);
  }   
  
  
  
  /********************************
   * SETTER & GETTER 
   ********************************/
  
  /**
   * sourceType setter
   * allowed types ZIP or PATH
   * @param string $type
   */
  public function setSourceType($type){
    $this->sourceType = (strtoupper($type)=='ZIP') ? 'ZIP' : 'PATH';
  }
  
  /**
   * sourceType getter
   * @return string
   */
  public function getSourceType(){
    return $this->sourceType;
  }
  
  /**
   * sourcePath setter
   * @param string $path
   */
  public function setSourcePath($path){
    if(file_exists($path)){
      $this->sourcePath = $path;
    }else{
      die("SOURCE PATH DOES NOT EXIST");
    }
  }
  
  /**
   * sourcePath getter
   * @return string
   */
  public function getSourcePath(){
    return $this->sourcePath;
  }
  
  
  /**
   * savePath setter
   * @param string $path
   */
  public function setSavePath($path){
    if(file_exists($path)){
      $this->savePath = $path;
    }else{
      die("SAVE PATH DOES NOT EXIST");
    }
  }
  
  /**
   * savePath getter
   * @return string
   */
  public function getSavePath(){
    return $this->savePath;
  }  
  
  
  /**
   * cssPrefix setter
   * @param string $val
   */
  public function setCssPrefix($val){
    $this->cssPrefix = $val;
  }
  
  /**
   * cssPrefix getter
   * @return string
   */
  public function getCssPrefix(){
    return $this->cssPrefix;
  }   
  
  
  /**
   * cssFormat setter
   * @param string $val
   */
  public function setCssFormat($val){
    $this->cssFormat = $val;
  }
  
  /**
   * cssFormat getter
   * @return string
   */
  public function getCssFormat(){
    return $this->cssFormat;
  }    
  
  
  /**
   * includeSubDir setter
   * @param bool $val
   */
  public function setIncludeSubDir($val){
    $this->includeSubDir = (bool)$val;
  }
  
  /**
   * includeSubDir getter
   * @return boolean
   */
  public function getIncludeSubDir(){
    return (bool)$this->includeSubDir;
  }  
  
  
  /**
   * spriteImageOffset setter
   * @param integer $val
   */
  public function setSpriteImageOffse($val){
    $this->spriteImageOffset = (int)$val;
  }
  
  /**
   * spriteImageOffset getter
   * @return integer
   */
  public function getSpriteImageOffset(){
    return $this->spriteImageOffset;
  }    
  
  /**
   * spriteMaxWidth setter
   * @param integer $val
   */
  public function setSpriteMaxWidth($val){
    $this->spriteMaxWidth = (int)$val;
  }
  
  /**
   * spriteMaxWidth getter
   * @return integer
   */
  public function getSpriteMaxWidth(){
    return $this->spriteMaxWidth;
  }   
  
  
  
  /**
   * debug helper method
   * @param mixed $var
   */
  public static function dbug($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }  
}

?>
