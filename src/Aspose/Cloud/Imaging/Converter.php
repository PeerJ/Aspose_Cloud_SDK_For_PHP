<?php
/**
 * Converts image into different formats.
 */ 
namespace Aspose\Cloud\Imaging;

use Aspose\Cloud\Common\AsposeApp;
use Aspose\Cloud\Common\Utils;
use Aspose\Cloud\Common\Product;
use Aspose\Cloud\Exception\AsposeCloudException as Exception;

class Converter {

    public $fileName = '';

    public function __construct($fileName) {
        $this->fileName = $fileName;
    }
    
    /**
     * Convert Image Format.
     * 
     * @param string $inputPath Input file path.
     * @param string $outputFormat Output file format.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertLocalFile($inputPath, $outputFormat) {
        //check whether files are set or not
        if ($inputPath == '')
            throw new Exception('Input file not specified');
        
        if ($outputFormat == '')
            throw new Exception('Format not specified');

        //build URI
        $strURI = Product::$baseProductUri . '/imaging/' . $this->fileName . '/saveAs?format=' . $outputFormat;

        //sign URI
        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::uploadFileBinary($signedURI, $inputPath, 'xml', 'POST');
        
        $v_output = Utils::validateOutput($responseStream);
        
        if ($v_output === '') {
            if ($outputFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $outputFormat;
            }
            
            $outputFilename = Utils::getFileName($inputPath) . '.' . $saveFormat;
            
            Utils::saveFile($responseStream, AsposeApp::$outPutLocation . $outputFilename);
            return $outputFilename;
        }
        else
            return $v_output;
    }
    
}    