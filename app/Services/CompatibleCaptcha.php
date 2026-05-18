<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Mews\Captcha\Captcha;

class CompatibleCaptcha extends Captcha
{
    public function create(string $config = 'default', bool $api = false)
    {
        $this->backgrounds = $this->files->files($this->bgsDirectory);
        $this->fonts = $this->files->files($this->fontsDirectory);

        if (version_compare(app()->version(), '5.5.0', '>=')) {
            $this->fonts = array_map(function ($file): string {
                return $file->getPathName();
            }, $this->fonts);
        }

        $this->fonts = array_values($this->fonts);
        $this->configure($config);

        $generator = $this->generate();
        $this->text = $generator['value'];

        $this->canvas = $this->imageManager->create($this->width, $this->height)->fill($this->bgColor);

        if ($this->bgImage) {
            $this->image = $this->imageManager->read($this->background())->resize(
                $this->width,
                $this->height,
            );
            $this->canvas->place($this->image);
        } else {
            $this->image = $this->canvas;
        }

        if ($this->contrast !== 0) {
            $this->image->contrast($this->contrast);
        }

        $this->text();
        $this->lines();

        if ($this->sharpen) {
            $this->image->sharpen($this->sharpen);
        }

        if ($this->invert) {
            $this->image->invert();
        }

        if ($this->blur) {
            $this->image->blur($this->blur);
        }

        Cache::put($this->get_cache_key($generator['key']), $generator['value'], $this->expire);

        $encodedImage = $this->image->encodeUsingMediaType('image/jpeg', $this->quality);

        return $api ? [
            'sensitive' => $generator['sensitive'],
            'key' => $generator['key'],
            'img' => (string) $encodedImage->toDataUri(),
        ] : new Response((string) $encodedImage, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'inline; filename="image.jpg"',
        ]);
    }

    protected function text(): void
    {
        $marginTop = $this->image->height() / $this->length;
        if ($this->marginTop !== 0) {
            $marginTop = $this->marginTop;
        }

        $text = is_string($this->text) ? str_split($this->text) : $this->text;

        foreach ($text as $key => $char) {
            $marginLeft = $this->textLeftPadding + ($key * ($this->image->width() - $this->textLeftPadding) / $this->length);

            $this->image->text($char, (int) $marginLeft, (int) $marginTop, function ($font): void {
                $font->file($this->font());
                $font->size($this->fontSize());
                $font->color($this->fontColor());
                $font->align('left', 'top');
                $font->angle($this->angle());
            });
        }
    }
}
