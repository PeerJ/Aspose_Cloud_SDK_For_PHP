<?php
/**
 * Created by PhpStorm.
 * User: AssadMahmood
 * Date: 2/24/14
 * Time: 2:59 PM
 */

namespace Aspose\Cloud\Imaging;

use Aspose\Cloud\Common\AsposeApp;
use Aspose\Cloud\Common\Utils;
use Aspose\Cloud\Common\Product;
use Aspose\Cloud\Storage\Folder;
use Aspose\Cloud\Exception\AsposeCloudException as Exception;

class Image {

    public function __construct($fileName){
        $this->fileName = $fileName;
    }
    
    /**
     * Converts Tiff image to Fax compatible format (TIFF-F specification) 
     * with scaling and padding.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function convertTiffToFax(){
        //check whether file is set or not
        if ($this->fileName == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/imaging/tiff/' . $this->fileName . '/toFax';

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');

        $v_output = Utils::validateOutput($responseStream);

        if ($v_output === '') {
            $outputPath = AsposeApp::$outPutLocation . $this->fileName;
            Utils::saveFile($responseStream, $outputPath);
            return $outputPath;
        }
        else
            return $v_output;
    }
    
    /**
     * Appends second tiff image to the original.
     * 
     * @param string $appendFile The tiff image file to append.
     * 
     * @return string|boolean 
     * @throws Exception
     */
    public function appendTiff($appendFile=""){
        //check whether file is set or not
        if ($this->fileName == '' || $appendFile == '')
            throw new Exception('No file name specified');

        $strURI = Product::$baseProductUri . '/imaging/tiff/' . $this->fileName . '/appendTiff?appendFile=' . $appendFile;

        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'POST', '', '');

        $json = json_decode($responseStream);

        if ($json->Status == 'OK') {
            $folder = new Folder();
            $outputStream = $folder->getFile($this->fileName);
            $outputPath = AsposeApp::$outPutLocation . $this->fileName;
            Utils::saveFile($outputStream, $outputPath);
            return $outputPath;
        } else {
            return false;
        }
    }
    
    /**
     * Resize Image without Storage.
     * 
     * @param integer $backgroundColorIndex Index of the background color.
     * @param integer $pixelAspectRatio Pixel aspect ratio.
     * @param boolean $interlaced Specifies if image is interlaced.
     * @param string $outPath Name of the output file.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function resizeImage($inputPath, $newWidth, $newHeight, $outputFormat) {
        //check whether files are set or not
        if ($inputPath == '')
            throw new Exception('Base file not specified');
        
        if ($newWidth == '')
            throw new Exception('New image width not specified');
        
        if ($newHeight == '')
            throw new Exception('New image height not specified');
        
        if ($outputFormat == '')
            throw new Exception('Format not specified');

        //build URI
        $strURI = Product::$baseProductUri . '/imaging/resize?newWidth=' . $newWidth . '&newHeight=' . $newHeight . '&format=' . $outputFormat;
        
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
    
    /**
     * Crop Image with Format Change.
     * 
     * @param integer $x X position of start point for cropping rectangle.
     * @param integer $y Y position of start point for cropping rectangle.
     * @param integer $width Width of cropping rectangle.
     * @param integer $height Height of cropping rectangle.
     * @param string $outPath Name of the output file.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function cropImage($x, $y, $width, $height, $outputFormat, $outPath) {
        //check whether files are set or not
        if ($this->fileName == '')
            throw new Exception('Base file not specified');
        
        if ($x == '')
            throw new Exception('X position not specified');
        
        if ($y == '')
            throw new Exception('Y position not specified');
        
        if ($width == '')
            throw new Exception('Width not specified');
        
        if ($height == '')
            throw new Exception('Height not specified');
        
        if ($outputFormat == '')
            throw new Exception('Format not specified');
        
        if ($outPath == '')
            throw new Exception('Output file name not specified');

        //build URI
        $strURI = Product::$baseProductUri . '/imaging/' . $this->fileName . '/crop?x=' . $x . '&y=' . $y . '&width=' . $width . '&height=' . $height . '&format=' . $outputFormat . '&outPath=' . $outPath;
        
        //sign URI
        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');
        
        $v_output = Utils::validateOutput($responseStream);
        
        if ($v_output === '') {
            if ($outputFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $outputFormat;
            }
            
            Utils::saveFile($responseStream, AsposeApp::$outPutLocation . $outPath);
            return $outPath;
        }
        else
            return $v_output;
    }
    
    /**
     * RotateFlip Image on Storage.
     * 
     * @param string $method RotateFlip method.
     * @param string $outputFormat Output file format.
     * @param string $outPath Name of the output file.
     * 
     * @return string Returns the file path.
     * @throws Exception
     */
    public function rotateImage($method, $outputFormat, $outPath) {
        //check whether files are set or not
        if ($this->fileName == '')
            throw new Exception('Base file not specified');
        
        if ($method == '')
            throw new Exception('RotateFlip method not specified');
        
        if ($outputFormat == '')
            throw new Exception('Format not specified');
        
        if ($outPath == '')
            throw new Exception('Output file name not specified');

        //build URI
        $strURI = Product::$baseProductUri . '/imaging/' . $this->fileName . '/rotateflip?method=' . $method . '&format=' . $outputFormat . '&outPath=' . $outPath;
       
        //sign URI
        $signedURI = Utils::sign($strURI);

        $responseStream = Utils::processCommand($signedURI, 'GET', '', '');
        
        $v_output = Utils::validateOutput($responseStream);
        
        if ($v_output === '') {
            if ($outputFormat == 'html') {
                $saveFormat = 'zip';
            } else {
                $saveFormat = $outputFormat;
            }
            
            Utils::saveFile($responseStream, AsposeApp::$outPutLocation . $outPath);
            return $outPath;
        }
        else
            return $v_output;
    }

} 