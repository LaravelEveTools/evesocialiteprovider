## LaravelEveTools\EveImages

Independent images tool to source eve images from EVE. Based on <a href="https://github.com/eveseat/services/blob/master/src/Image/Eve.php">eveseat/services/Image/Eve</a> but without all the seat specific stuff.

## Basic Usage

```PHP
use LaraveEveTools\EveImages\Image;
//Generate a full html <img> tag
(new Image('characters', $character_id, 64))->html()
//Generate image url
(new Image('characters', $character_id, 64))->url()
```

## Variables
Name|Type|Description|Default
--------|--------|--------|--------
type|string|See Allowed Types|Required
id|Integer|The ID of the Entity|Required
size|Integer|See Allowed Sizes|Required
attrs|Array|array of HTML attributes|[ ]
lazy|Boolean|Lazy load Image | false

### Type
Allows the following options.

[ 'characters', 'corporations', 'alliances', 'factions' ]


### Size
Allows the following options

[ 32, 64, 128, 256, 512, 1024 ]

### lazy
If using lazy loading, you will need to handle the loading of the image from the front end.<br>
The url of the image will be stored with the 'data-src' attribute.


