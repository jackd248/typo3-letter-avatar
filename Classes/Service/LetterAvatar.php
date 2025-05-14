<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Service;

use InvalidArgumentException;
use KonradMichalik\Typo3LetterAvatar\Configuration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LetterAvatar
{
    /**
     * Image Type PNG
     */
    const MIME_TYPE_PNG = 'image/png';

    /**
     * Image Type JPEG
     */
    const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * @var string
     */
    private string $name;


    /**
     * @var string
     */
    private string $nameInitials;


    /**
     * @var string
     */
    private string $shape;


    /**
     * @var int
     */
    private int $size;

    /**
     * @var string
     */
    private string $backgroundColor = '';

    /**
     * @var string
     */
    private string $foregroundColor = '';

    /**
     * @param string $name
     * @param string $shape
     * @param int $size
     */
    public function __construct(string $name, string $shape = 'circle', int $size = 48)
    {
        $this->setName($name);
        $this->setShape($shape);
        $this->setSize($size);
    }

    /**
     * @param $backgroundColor
     * @param $foregroundColor
     */
    public function setColor($backgroundColor, $foregroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        $this->foregroundColor = $foregroundColor;
        return $this;
    }

    /**
     * @param string $name
     */
    private function setName(string $name)
    {
        $this->name = $name;
    }


    /**
     * @param \Gmagick|\Imagick $imageManager
     */
    private function setImageManager(\Gmagick|\Imagick $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param string $shape
     */
    private function setShape(string $shape)
    {
        $this->shape = $shape;
    }


    /**
     * @param int $size
     */
    private function setSize(int $size)
    {
        $this->size = $size;
    }

    private function generate(): \Imagick
    {
        $isCircle = $this->shape === 'circle';

        $this->nameInitials = $this->getInitials($this->name);
        $this->backgroundColor = $this->backgroundColor ?: $this->stringToColor($this->name);
        $this->foregroundColor = $this->foregroundColor ?: '#fafafa';

        $canvas = new \Imagick();
        $canvas->newImage(480, 480, $isCircle ? new \ImagickPixel('transparent') : new \ImagickPixel($this->backgroundColor));
        $canvas->setImageFormat('png');

        if ($isCircle) {
            $circle = new \ImagickDraw();
            $circle->setFillColor(new \ImagickPixel($this->backgroundColor));
            $circle->circle(240, 240, 240, 0);
            $canvas->drawImage($circle);
        }

        $text = new \ImagickDraw();
        $text->setFont(GeneralUtility::getFileAbsFileName('EXT:' . Configuration::EXT_KEY . '/Resources/Public/Fonts/arial-bold.ttf'));
        $text->setFontSize(220);
        $text->setFillColor(new \ImagickPixel($this->foregroundColor));
        $text->setTextAlignment(\Imagick::ALIGN_CENTER);
        $text->annotation(240, 320, $this->nameInitials);

        $canvas->drawImage($text);
        $canvas->resizeImage($this->size, $this->size, \Imagick::FILTER_LANCZOS, 1);

        return $canvas;
    }

    /**
     * @param string $name
     * @return string
     */
    private function getInitials(string $name): string
    {
        $nameParts = $this->breakName($name);

        if (!$nameParts) {
            return '';
        }

        $secondLetter = isset($nameParts[1]) ? $this->getFirstLetter($nameParts[1]) : '';

        return $this->getFirstLetter($nameParts[0]) . $secondLetter;

    }

    /**
     * @param string $word
     * @return string
     */
    private function getFirstLetter(string $word): string
    {
        return mb_strtoupper(trim(mb_substr($word, 0, 1, 'UTF-8')));
    }

    public function saveAs($path, $mimetype = self::MIME_TYPE_PNG, $quality = 90): bool
    {
        if (empty($path)) {
            return false;
        }

        if (!in_array($mimetype, [self::MIME_TYPE_PNG, self::MIME_TYPE_JPEG], true)) {
            throw new InvalidArgumentException("Invalid mimetype: $mimetype");
        }

        $image = $this->generate();
        $image->setImageFormat($mimetype === self::MIME_TYPE_JPEG ? 'jpeg' : 'png');

        if ($mimetype === self::MIME_TYPE_JPEG) {
            $image->setImageCompressionQuality($quality);
        }

        return $image->writeImage($path);
    }

    /**
     * @param string $name Name to be broken up
     * @return array Name broken up to an array
     */
    private function breakName(string $name): array
    {
        return array_values(array_filter(explode(' ', $name), fn($word) => $word !== '' && $word !== ','));
    }

    /**
     * @param string $string
     * @return string
     */
    private function stringToColor(string $string): string
    {
        $rgb = substr(hash('crc32b', $string), 0, 6);
        $darker = 2;

        $R = sprintf('%02X', hexdec(substr($rgb, 0, 2)) / $darker);
        $G = sprintf('%02X', hexdec(substr($rgb, 2, 2)) / $darker);
        $B = sprintf('%02X', hexdec(substr($rgb, 4, 2)) / $darker);

        return "#$R$G$B";
    }
}
