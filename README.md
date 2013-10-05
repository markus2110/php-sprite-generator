#php sprite generator

This PHP library generates a sprite image and all necessary css classes


##Useage

###Use option setters
```php
$Sprite = new SpriteGenerator();
$Sprite->setScanSubDir(true);
$Sprite->setEnableFilters(true);
$Sprite->setSpriteMaxWidth(500);
$Sprite->setSpriteImageOffset(1);
$Sprite->generate();
```

####Method chaining
```php
$Sprite = new SpriteGenerator();
$Sprite->setScanSubDir(true)
       ->setEnableFilters(true)
       ->setSpriteMaxWidth(500)
       ->setSpriteImageOffset(1)
       ->generate();
```

####or set constructor options
```php
$options = array(
    'SourcePath'  => $sourcePath,
    'SavePath'    => $savePath,
    'ScanSubDir'  => false,
    'enableFilters' => false,
    'SpriteImageOffset' => 0
);
$Sprite = new SpriteGenerator($options);
$Sprite->generate();
```


##Methods
```php
$Sprite->setCssFormat(string)        default="%s {background:url(%s) %dpx %dpx no-repeat;height:%dpx;width:%dpx}"
$Sprite->setCssPrefix(string)        default="mysprite"
$Sprite->setEnableFilters(boolean)   default=true
$Sprite->setOptions(array)
$Sprite->setSavePath(string)
$Sprite->setScanSubDir(boolean)      default=false
$Sprite->setSourcePath(string)
$Sprite->setSpriteImageOffset(int)   default=2
$Sprite->setSpriteMaxWidth(int)      default=500
```