<?php
/**
 * converts pages or document into different formats
 */
namespace Aspose\Cloud\Words;

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

        $this->saveFormat = 'Doc';
    }

    /**
     * Convert a document to SaveFormat using Aspose storage.
     *
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convert($folder = null) {
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        //build URI
        $strURI = Product::$baseProductUri . '/words/' . $this->fileName . '?format=' . $this->saveFormat;
        if ($folder) {
            $strURI = $strURI . "&folder=" . urlencode($folder);
        }

        //sign URI
        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($this->saveFormat == 'html') {
                $save_format = 'zip';
            } else {
                $save_format = $this->saveFormat;
            }
            $outputPath = AsposeApp::$outPutLocation . Utils::getFileName($this->fileName) . '.' . $save_format;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        } else {
            return $v_output;
        }
    }
    
    /**
     * Convert a document to SaveFormat without using Aspose storage.
     * 
     * @param type $inputPath The path of source file.
     * @param type $outputPath Path where you want to file after conversion.
     * @param type $outputFormat New file format.
     * 
     * @return string Returns the file path.  
     */
    public function convertLocalFile($inputPath, $outputPath, $outputFormat) {
        $str_uri = Product::$baseProductUri . '/words/convert?format=' . $outputFormat;
        $signed_uri = Utils::sign($str_uri);
        $responseStream = Utils::uploadFileBinary($signed_uri, $inputPath, 'xml');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            if ($outputFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $outputFormat;
            }

            if ($outputPath == '') {
                $outputFilename = Utils::getFileName($inputPath) . '.' . $saveFormat;
            }

            Utils::saveFile($responseStream, AsposeApp::$outPutLocation . $outputFilename);
            return $outputFilename;
        }
        else
            return $v_output;
    }
}
