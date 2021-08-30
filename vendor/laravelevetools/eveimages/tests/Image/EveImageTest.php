<?php

use Illuminate\Contracts\Console\Kernel;
use LaravelEveTools\EveImages\Exceptions\EveImageException;
use LaravelEveTools\EveImages\Image;
use Orchestra\Testbench\TestCase;


class EveImageTest extends TestCase
{

    public function testNotValidParameters(){
        $this->expectException(EveImageException::class);
        new Image('Train', 0, 25);
    }

    /**
     * @throws EveImageException
     */
    public function testUrlFunction(){
        $image = new Image('characters', 1338057886, 64);

        $this->assertEquals(
            "//images.evetech.net/characters/1338057886/portrait?size=64",
            $image->url()
        );

        $image = new Image('corporations', 1092999958, 64);
        $this->assertEquals(
            "//images.evetech.net/corporations/1092999958/logo?size=64",
            $image->url()
        );

        $image = new Image('alliances', 434243723, 64);
        $this->assertEquals(
            "//images.evetech.net/alliances/434243723/logo?size=64",
            $image->url()
        );

        $image = new Image('factions', 434243723, 64);
        $this->assertEquals(
            "//images.evetech.net/alliances/434243723/logo?size=64",
            $image->url()
        );

        $image = new Image('types', 587, 64);
        $this->assertEquals(
            "//images.evetech.net/types/587/icon?size=64",
            $image->url()
        );

        $image = new Image('types', 587, 256);
        $this->assertEquals(
            "//images.evetech.net/types/587/render?size=256",
            $image->url()
        );
    }

    /**
     * @throws EveImageException
     */
    public function testHtmlFunction(){
        $image = new Image('characters', 1338057886, 64, ['class'=>'image'], true);

        $html = $image->html();
        $this->assertStringContainsString(
            'class="image img-lazy-load"',
            $html
        );

        $this->assertStringContainsString(
            'data-src="'.$image->url().'"',
            $html
        );
    }
}
