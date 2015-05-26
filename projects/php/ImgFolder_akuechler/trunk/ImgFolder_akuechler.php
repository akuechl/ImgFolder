<?php
/**
 * @package      Joomla
 * @copyright    Copyright (C) 2011-2014 Ariel K�chler. All rights reserved.
 * @license      MIT License (http://opensource.org/licenses/mit-license copyright information see above) OR GPL-3.0 (http://opensource.org/licenses/gpl-3.0)
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.plugin.plugin' );

class plgContentImgFolder_akuechler extends JPlugin {

    function plgContentImgFolder_akuechler(&$subject, $config) {
        parent::__construct ( $subject, $config );
    }

    function onContentPrepare($context, &$row, &$params, $limitstart = 0) {
        // fast fail
        $app = & JFactory::getApplication ();
        if ($app->isAdmin () || (JString::strpos ( $row->text, 'data-folder-loop' ) === false)) {
            return true;
        }
        
        $matches = null;
        // |<([^\s>]+)[^>]*\bdata-folder-loop=(["']?)([^"'\s]+)\2[^>]*>(.*?)</\1>|i
        $regex = '|<([^\\s>]+)[^>]*\\bdata-folder-loop=(["\']?)([^"\'\\s]+)\\2[^>]*>(.*?)</\\1>|i';
        preg_match_all ( $regex, $row->text, $matches, PREG_SET_ORDER);
        $count = count ( $matches );
        
        // plugin only processes if there are any instances of the plugin in the text
        if ($count) {
        	// test string: 
        	// <span>test</span><div><pp id="ignore" class="x folder-loop y" data-folder-loop='images/test/' id="2"><img src="{0}" width="100" height="100" alt="" class="" /></pp></div><span>hallo</span>
            for($i = 0; $i < $count; $i ++) {
                $row->text = str_replace ( $matches [$i] [4], $this->_getReplacment ( $matches [$i] [3], $matches [$i] [4] ), $row->text );
            }
        }
        return true;
    }
    
     function _getReplacment(&$url, &$match) {
        $base = "";
        // if not absolute path: set base path, base path ends with "/"
        if ( ! $this->_startsWith($url, '/')) {
            $base = JURI::base( true );
            $base .= '/';
        }
        // url ends with "/"
        if ( ! $this->_endsWith($url, '/')) {
            $url .= '/';
        }
        // loop tru folder and collect file names
        $folder = getcwd() . $base . $url;
        $names = array();
        if (is_dir($folder)) {
            foreach (new DirectoryIterator($folder) as $file) {
                if($file->isDot() || !$file->isFile() || $file->getFilename() === 'index.html') continue;
                $names[] = $file->getFilename();
            }
        }
        // sort natural order
        natcasesort($names);
        // iterate file names and concat $match, replace placeholder in $match
        $result = "";
        foreach ($names as $name) {
                $result .= str_replace("[0]", $url . $name, $match);
        }
        return $result;
    }

    // http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    function _startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    // http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
    function _endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
}
?>
