<?php

namespace LaravelEveTools\EveImages;

use LaravelEveTools\EveImages\Exceptions\EveImageException;

class Image
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $variation;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var bool
     */
    protected $lazy;

    /**
     * @var string[]
     */
    protected $known_types = [

        'characters', 'corporations', 'alliances', 'factions', 'types', 'render'
    ];

    /**
     * @var int[]
     */
    protected $valid_sizes = [
        32, 64, 128, 256, 512, 1024
    ];

    /**
     * @var string
     */
    protected $img_server = '//images.evetech.net';

    /**
     * @param string $type
     * @param int $id
     * @param int $size
     * @param array $attr
     * @param bool $lazy
     * @throws EveImageException
     */
    public function __construct(
        string $type,
        int $id,
        int $size,
        array $attr = [],
        bool $lazy = true
    )
    {
        //Validate Type
        if(! in_array($type, $this->known_types))
            throw new EveImageException($type.' is not a valid image type.');

        //Validate Type ID
        if(! is_int($id))
            throw new EveImageException('id must be an integer.');

        //Validate Size
        if(! is_int($size))
            throw new EveImageException('size must be an integer');

        if(! in_array($size,$this->valid_sizes))
            throw new EveImageException("unsupported image size");

        switch($type){
            case 'characters':
                $this->variation = 'portrait';
                break;
            case 'corporations':
            case 'alliances':
            case 'factions':
                $this->variation = 'logo';
                break;
            default:
                $this->variation = $size > 64 ? 'render':'icon';
        }

        //Faction images are stored with alliances
        if($type == 'factions')
            $type = 'alliances';

        $this->type = $type;
        $this->id = $id;
        $this->attributes = $attr;
        $this->lazy = $lazy;
        $this->size = $size;
    }

    /**
     * Create full image HTML
     */
    public function html($size = null): string
    {
        $attrs = [];
        $classes = [];
        if(!is_null($size)){
            $this->size = $size;
        }
        if($this->lazy)
        {
            //add tags for image lazy loading
            //this has to be handles on the front end.
            $attrs[] = 'src="'.asset('eveimages/bg.png').'"';
            $attrs[] = 'data-src="' . $this->url($this->size) .'"';

            $attrs[] = 'data-src-retina="'.$this->url($this->size == 1024 ? 1024 : $this->size * 2) . '"';

            if(isset($this->attributes['class'])){
                $classes = is_array($this->attributes['class']) ?
                    $this->attributes['class'] :
                    [$this->attributes['class']];
            }
            $classes[] = 'img-lazy-load';
        }else{
            $attrs[] = 'src="'.$this->url($this->size).'"';
        }
        $attrs[] = 'class="'.implode(' ', $classes).'"';

        return '<img '.implode(' ', $attrs).'>';
    }

    //Generate the URL
    public function url($size = null): string
    {
        return sprintf('%s/%s/%d/%s?size=%d', $this->img_server, $this->type, $this->id, $this->variation, is_null($size) ? $this->size : $size);
    }
}
