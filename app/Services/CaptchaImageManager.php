<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\AnimationFactoryInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\ImageInterface;

class CaptchaImageManager extends ImageManager
{
    public function __construct(string|DriverInterface $driver, mixed ...$options)
    {
        parent::__construct($driver, ...$options);
    }

    public function create(
        int $width,
        int $height,
        null|callable|AnimationFactoryInterface $animation = null,
    ): ImageInterface {
        return $this->createImage($width, $height, $animation);
    }
}
