<?php
/**
 * Converts pages or document into different formats.
 */
namespace Aspose\Cloud\Slides;

use Aspose\Cloud\Common\AsposeApp;
use Aspose\Cloud\Common\Utils;
use Aspose\Cloud\Common\Product;
use Aspose\Cloud\Exception\AsposeCloudException as Exception;

class Converter {

    public $fileName = '';
    public $saveFormat = '';

    public function __construct($fileName) {
        //set default values
        $this->fileName = $fileName;

        $this->saveFormat = 'PPT';
    }

    /**
     * Saves a particular slide into various formats with specified width and height.
     * 
     * @param integer $slideNumber The number of slide.
     * @param string $imageFormat The image format.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertToImage($slideNumber, $imageFormat) {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/slides/' . $this->fileName . '/slides/' . $slideNumber . '?format=' . $imageFormat;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');
        $v_output = Utils::validateOutput($responseStream);
        if ($v_output == '') {
            $outputPath = AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '.' . $imageFormat;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        } else {
            return $v_output;
        }
    }

    /**
     * Convert a particular slide into various formats with specified width and height.
     * 
     * @param integer $slideNumber The slide number.
     * @param string $imageFormat The image format.
     * @param integer $width The width of image.
     * @param integer $height The height of image.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertToImagebySize($slideNumber, $imageFormat, $width, $height) {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/slides/' . $this->fileName . '/slides/' . $slideNumber . '?format=' . $imageFormat . '&width=' . $width . '&height=' . $height;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');
        $v_output = Utils::validateOutput($responseStream);
        if ($v_output == '') {
            $outputPath = AsposeApp::$outPutLocation . 'output.' . $imageFormat;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        } else {
            return $v_output;
        }
    }

    /**
     * Convert a document to the specified format.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convert() {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/slides/' . $this->fileName . '?format=' . $this->saveFormat;

        $signedURI = Utils::sign($strURI);
        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($this->saveFormat == 'html') {
                $save_format = 'zip';
            } else {
                $save_format = $this->saveFormat;
            }
            $outputPath =  AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '.' . $save_format;
            Utils::saveFile($responseStream,$outputPath);
            return $outputPath;
        } else {
            return $v_output;
        }
    }
}