<?php
/**
 * Converts pages or document into different formats.
 */
namespace Aspose\Cloud\Pdf;

use Aspose\Cloud\Common\AsposeApp;
use Aspose\Cloud\Common\Utils;
use Aspose\Cloud\Common\Product;
use Aspose\Cloud\Exception\AsposeCloudException as Exception;

class Converter {

    public $fileName = '';
    public $saveFormat = '';

    public function __construct($fileName) {
        $this->fileName = $fileName;

        $this->saveFormat = 'Pdf';
    }

    /**
     * Convert a particular page to image with specified size.
     * 
     * @param integer $pageNumber The document page number.
     * @param string $imageFormat Return the document in the specified format.
     * @param integer $width The width of image.
     * @param integer $height The height of image.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertToImagebySize($pageNumber, $imageFormat, $width, $height) {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/pdf/' . $this->fileName . '/pages/' . $pageNumber . '?format=' . $imageFormat . '&width=' . $width . '&height=' . $height;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            $outputPath = AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '_' . $pageNumber . '.' . $imageFormat;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        }
        else
            return $v_output;
    }

    /**
     * Convert a particular page to image with default size.
     * 
     * @param integer $pageNumber The document page number.
     * @param string $imageFormat Return the document in the specified format.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertToImage($pageNumber, $imageFormat) {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/pdf/' . $this->fileName . '/pages/' . $pageNumber . '?format=' . $imageFormat;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            $outputPath = AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '_' . $pageNumber . '.' . $imageFormat;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        }
        else
            return $v_output;
    }

    /**
     * Convert a document by url to SaveFormat.
     * 
     * @param string $url URL of the document.
     * @param string $outputFilename The name of output file.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertByUrl($url='',$format='',$outputFilename='') {
        //check whether file is set or not
        if ($url == '')
            throw new Exception('Url not specified');

        $strURI = Product::$baseProductUri . '/pdf/convert?url='.$url.'&format='.$format;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'PUT', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($this->saveFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $this->saveFormat;
            }

            $outputPath = Utils::saveFile($responseStream, AsposeApp::$outPutLocation . Utils::getFileName($outputFilename) . '.' . $format);
            return $outputPath;
        } else {
            return $v_output;
        }
    }

    /**
     * Convert a document to SaveFormat using Aspose cloud storage.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convert() {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/pdf/' . $this->fileName . '?format=' . $this->saveFormat;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($this->saveFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $this->saveFormat;
            }

            $outputPath = Utils::saveFile($responseStream, AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '.' . $saveFormat);
            return $outputPath;
        } else {
            return $v_output;
        }
    }

    /**
     * Convert PDF to different file format without using Aspose cloud storage.
     * 
     * $param string $inputFile The path of source file.
     * @param string $outputFilename The output file name.
     * @param string $outputFormat Returns document in the specified format.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertLocalFile($inputFile = '', $outputFilename = '', $outputFormat = '') {
        //check whether file is set or not
        if ($inputFile == '')
            throw new Exception('No file name specified');

        if ($outputFormat == '')
            throw new Exception('output format not specified');


        $strURI = Product::$baseProductUri . '/pdf/convert?format=' . $outputFormat;

        if (!file_exists($inputFile)) {
            throw new Exception('input file doesnt exist.');
        }


        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::uploadFileBinary($signedURI, $inputFile, 'xml');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($outputFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $outputFormat;
            }

            if ($outputFilename == '') {
                $outputFilename = Utils::getFileName($inputFile) . '.' . $saveFormat;
            }
            $outputPath = AsposeApp::$outPutLocation . $outputFilename;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        }
        else
            return $v_output;
    }

}