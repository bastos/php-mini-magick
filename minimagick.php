<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
*
* A port for Ruby phpMiniMagick
*
* PHP versions 5
*
* LICENSE: This source file is subject to version 3.0 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_0.txt.  If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category   Images
* @package    phpMiniMagick
* @author     Tiago Bastos <comechao@gmail.com>
* @copyright  2007 Tiago Bastos
* @license    http://www.php.net/license/3_0.txt  PHP License 3.0
* @version   1.0
* @link       http://tiago.zusee.com
* @since      1
*/


/**
* Classe to wrap imagemagick convert and mogrify'
*
* A little port of ruby MiniMagick.
*
* @category   Images
* @package    phpMiniMagick
* @author     Tiago Bastos <comechao@gmail.com>
* @copyright  2007 Tiago Bastos
* @license    http://www.php.net/license/3_0.txt  PHP License 3.0
* @version   1.0
* @link       http://tiago.zusee.com
* @since      1
*
* Convert example:
*<code>
* $t = new pMagick('./rose.jpg',false);
* if ($t->resize('50%','images/rose_50.png')){
*      print 'ok';
* } else {
*     print 'error :\\';
* }
*
*</code>
* This example is equal to: shell# convert rose.jpg -resize 50% rose_59.png
*
*
* Mogrify example:
*<code>
* $t = new pMagick('./rose.jpg');
* if ($t->resize('50%'){
*      print 'ok';
* } else {
*     print 'error :\\';
* }
*</code>
*  This example is equal to : shell# mogrify -resize 50% rose.jpg
*
* Read more at: 
* @link http://www.imagemagick.org/script/index.php
*/

class pMagick
{

    /** 
    * path
    * Path da imagem
    * @access public 
    * @name $path 
    */ 
    public $path;
    
    /** 
    * Contructor
    * @access public 
    * @param String $path
    * @param String $use_mogrify
    * @return void 
    */ 
    public function __construct($path, $use_mogrify=true){
        $this->path  = $path;
        $this->use_mogrify = $use_mogrify;
    }

    public function getMethods()
    {
        return $this->_methods;
    }

    private function __call( $f, $a ){
        if (method_exists($this, $f)) {
            return $this->{$f}($a);
        } else {
            $f = str_replace('_','-',$f);
            if ($this->use_mogrify) {
                $c = "mogrify  -{$f} ".implode(' ',$a)." {$this->path}";
            } else {
                $c = "convert {$this->path} -{$f}  ".implode(' ',$a)." ";
            }                            
            exec($c,$o,$r);
            if ($r!=0)
                return false;
            else
                return true;
        }
    }

    public function version(){
        return '0.1 alpha';
    }

    private function __toString()
    {
        return '#'.$this->image;
    }
    
    /** 
    * Dimensions
    * Return image dimensions
    * @access public 
    * @return array  Image Dimensions
    */    
    public function dimensions(){
        $cmd = "identify '" . $this->path . "' 2>/dev/null";
        $results = exec($cmd);
        $results = trim($results);
        $results = explode(" ", $results);
        foreach ($results as $i=> $result) {
              if (preg_match("/^[0-9]*x[0-9]*$/", $result)) {
                    $results = explode("x", $result);
                    break;
              }
        }
        $dimensions['height'] = $results[1];
        $dimensions['width'] = $results[0];    
        return $dimensions;
    }
}
 ?>


