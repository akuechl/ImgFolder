<?php
/**
 * @package      Joomla
 * @copyright    Copyright (C) 2011-2014 Ariel Küchler. All rights reserved.
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
    	// TODO auslesen + loop
    	$base = JURI::base( true );
    	$url = $base . $url . "x.jpg";
    	return str_replace("{0}", $url, $match);
    }
}
?>
