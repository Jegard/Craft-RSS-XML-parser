<?php
/**
 * RSS/Atom Reader plugin for Craft CMS
 *
 * RSS/Atom Reader Variable
 *
 * --snip--
 * Craft allows plugins to provide their own template variables, accessible from the {{ craft }} global variable
 * (e.g. {{ craft.pluginName }}).
 *
 * https://craftcms.com/docs/plugins/variables
 * --snip--
 *
 * @author    Luca Jegard
 * @copyright Copyright (c) 2017 Luca Jegard
 * @link      https://github.com/Jegard
 * @package   RssatomReader
 * @since     1.0.0
 */

namespace Craft;

class RssatomReaderVariable
{
    /**
     * Whatever you want to output to a Twig template can go into a Variable method. You can have as many variable
     * functions as you want.  From any Twig template, call it like this:
     *
     *     {{ craft.rssatomReader.exampleVariable }}
     *
     * Or, if your variable requires input from Twig:
     *
     *     {{ craft.rssatomReader.exampleVariable(twigValue) }}86400
     */
    public function feed($url = null, $cache_duration = 86400, $timeout = 1000)
    {
      if( file_exists(__DIR__.'/cache.txt') ){
        if( time() > (filemtime(__DIR__.'/cache.txt') + $cache_duration) ){
          //cache time has passed
          $feed = $this->get_feed( $url, $timeout );
        }else{
          $feed = file_get_contents(__DIR__.'/cache.txt');
        }
      }else{
        $feed = $this->get_feed( $url, $timeout );
      }

      $xml = simplexml_load_string($feed);
      $xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
      return !$feed?$feed:$xml_array;
    }
    public function get_feed($url, $timeout) {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
      curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);

      $data = curl_exec($ch);
      $info = curl_getinfo($ch);

      var_dump($info['http_code']);
      if($info['http_code'] == 200) {
        file_put_contents(__DIR__.'/cache.txt',$data);
      }else{
        return false;
      }

      curl_close($ch);
      return $data;
   }
}
