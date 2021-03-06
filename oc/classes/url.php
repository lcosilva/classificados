<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL helper class.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class URL extends Kohana_URL {


    /**
     * Convert a phrase to a URL-safe title. Overwriten original to ascii only depending on language
     *
     *     echo URL::title('My Blog Post'); // "my-blog-post"
     *
     * @param   string   $title       Phrase to convert
     * @param   string   $separator   Word separator (any single character)
     * @param   boolean  $ascii_only  Transliterate to ASCII?
     * @return  string
     * @uses    UTF8::transliterate_to_ascii
     */
    public static function title($title, $separator = '-', $ascii_only = NULL)
    {
        //using the slugify function to get rid and replace special chars
        $res = self::slugify($title,$separator);
   
        //in case sludigy returns empty because the usage of CJK characters....somewhere in the title
        return (strlen($res)==0 AND strlen($title)>0) ? self::cjk_slugify($title,$separator):$res;      
    }

    /**
     * Fetches an absolute site URL based on a URI segment.
     *
     *     echo URL::site('foo/bar');
     *
     * @param   string  $uri        Site URI to convert
     * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
     * @param   boolean $index      Include the index_page in the URL
     * @return  string
     * @uses    URL::base
     */
    public static function site($uri = '', $protocol = NULL, $index = TRUE, $subdomain = NULL)
    {
        // Chop off possible scheme, host, port, user and pass parts
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));

        // Encode all non-ASCII characters, as per RFC 1738
        if(mb_detect_encoding($path,'ASCII')===TRUE)
        {
            $path = parent::title($path, '-', TRUE);
        }

        // Concat the URL
        return URL::base($protocol, $index).$path;
    }

    /**
     * Test if given $host should be trusted.
     *
     * Tests against given $trusted_hosts
     * or looks for key `trusted_hosts` in `url` config
     *
     * @param string $host
     * @param array $trusted_hosts
     * @return boolean TRUE if $host is trustworthy
     */
    public static function is_trusted_host($host, array $trusted_hosts = NULL)
    {
        return TRUE;
    }

    /**
     * returns the current url we are visiting with querystring included
     * @return string
     */
    public static function current()
    {
        //in case is  CLI
        if (!isset($_SERVER['QUERY_STRING']) OR Request::$current == NULL OR defined('SUPPRESS_REQUEST'))
            return URL::base();

        return substr(URL::base(),0,-1) . $_SERVER['REQUEST_URI'];
    }

    /**
     * gets the domain name from a full domain, strips subdomains.
     * @param  string $domain 
     * @return string         
     */
    public static function get_domain($domain)
    {
        if (!class_exists('Novutec\DomainParser\Parser'))
            require Kohana::find_file('vendor/DomainParser', 'Parser');

        $Parser = new Novutec\DomainParser\Parser();
        $fqdn_domain = $Parser->parse($domain)->fqdn;

        if (!empty($fqdn_domain) AND $fqdn_domain != NULL )
            return $fqdn_domain;
        //failback in case the FQDN parser fails see https://github.com/open-classifieds/open-eshop/issues/508
        elseif ( preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
            return $regs['domain']; 
           
        //something went really wrong :S
        return FALSE;
    }


    /**
     * from https://github.com/keyvanakbary/slugifier/blob/v3.0.0/src/slugifier.php
     */

    public static $chars_map = array(
        // Latin
        '??' => '0', '??' => 'ae', '??' => 'ae', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A',
        '??' => 'A', '??' => 'A', '??' => 'AE', '??' => 'AE', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a',
        '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '@' => 'at', '??' => 'C', '??' => 'C', '??' => 'c', '??' => 'c',
        '??' => 'c', '??' => 'Dj', '??' => 'D', '??' => 'dj', '??' => 'd', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E',
        '??' => 'E', '??' => 'E', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'f',
        '??' => 'G', '??' => 'G', '??' => 'g', '??' => 'g', '??' => 'H', '??' => 'H', '??' => 'h', '??' => 'h', '??' => 'I',
        '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'IJ', '??' => 'i',
        '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'ij', '??' => 'J',
        '??' => 'j', '??' => 'L', '??' => 'L', '??' => 'L', '??' => 'l', '??' => 'l', '??' => 'l', '??' => 'N', '??' => 'n',
        '??' => 'n', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
        '??' => 'O', '??' => 'O', '??' => 'OE', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o',
        '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'oe', '??' => 'R', '??' => 'R', '??' => 'r',
        '??' => 'r', '??' => 'S', '??' => 'S', '??' => 's', '??' => 's', '??' => 's', '??' => 'T', '??' => 'T', '??' => 'T',
        '??' => 'TH', '??' => 't', '??' => 't', '??' => 't', '??' => 'th', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U',
        '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U',
        '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u',
        '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'W', '??' => 'w', '??' => 'Y', '??' => 'Y', '??' => 'Y',
        '??' => 'y', '??' => 'y', '??' => 'y',

        // Greek
        '??' => 'A', '??' => 'B', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Z', '??' => 'I', '??' => 'Th', '??' => 'I',
        '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'Ks', '??' => 'O', '??' => 'P', '??' => 'R', '??' => 'S',
        '??' => 'T', '??' => 'Y', '??' => 'Ph', '??' => 'Ch', '??' => 'Ps', '??' => 'O', '??' => 'I', '??' => 'Y', '??' => 'a',
        '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'Y', '??' => 'a', '??' => 'b', '??' => 'g', '??' => 'd', '??' => 'e',
        '??' => 'z', '??' => 'i', '??' => 'th', '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => 'ks',
        '??' => 'o', '??' => 'p', '??' => 'r', '??' => 's', '??' => 's', '??' => 't', '??' => 'y', '??' => 'ph', '??' => 'x',
        '??' => 'ps', '??' => 'o', '??' => 'i', '??' => 'y', '??' => 'o', '??' => 'y', '??' => 'o', '??' => 'b', '??' => 'th',
        '??' => 'Y',

        // Turkish
        '??' => 'C', '??' => 'G', '??' => 'I', '??' => 'S', '??' => 'c', '??' => 'g', '??' => 'i', '??' => 's',

        // Czech
        '??' => 'C', '??' => 'D', '??' => 'E', '??' => 'N', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'Z',
        '??' => 'c', '??' => 'd', '??' => 'e', '??' => 'n', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u', '??' => 'z',

        // Arabic
        '??' => 'a', '??' => 'b', '??' => 't', '??' => 'th', '??' => 'g', '??' => 'h', '??' => 'kh', '??' => 'd', '??' => 'th',
        '??' => 'r', '??' => 'z', '??' => 's', '??' => 'sh', '??' => 's', '??' => 'd', '??' => 't', '??' => 'th', '??' => 'aa',
        '??' => 'gh', '??' => 'f', '??' => 'k', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => 'h', '??' => 'o',
        '??' => 'y',

        // Vietnamese
        '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a',
        '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e',
        '???' => 'e', '???' => 'e', '???' => 'i', '???' => 'i', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o',
        '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'u', '???' => 'u',
        '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'y', '???' => 'y', '???' => 'y', '???' => 'y',
        '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'A',
        '???' => 'A', '???' => 'A', '???' => 'A', '???' => 'E', '???' => 'E', '???' => 'E', '???' => 'E', '???' => 'E', '???' => 'E',
        '???' => 'E', '???' => 'E', '???' => 'I', '???' => 'I', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O',
        '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'O', '???' => 'U', '???' => 'U',
        '???' => 'U', '???' => 'U', '???' => 'U', '???' => 'U', '???' => 'U', '???' => 'Y', '???' => 'Y', '???' => 'Y', '???' => 'Y',

        // Polish
        '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'L', '??' => 'N', '??' => 'O', '??' => 'S', '??' => 'Z', '??' => 'Z',
        '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'l', '??' => 'n', '??' => 'o', '??' => 's', '??' => 'z', '??' => 'z',

        // Latvian
        '??' => 'A', '??' => 'E', '??' => 'G', '??' => 'I', '??' => 'K', '??' => 'L', '??' => 'N', '??' => 'U', '??' => 'a',
        '??' => 'e', '??' => 'g', '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'n', '??' => 'u',

        // German
        '??' => 'AE', '??' => 'OE', '??' => 'UE', '??' => 'ss', '??' => 'ae', '??' => 'oe', '??' => 'ue',

        // Ukrainian
        '??' => 'G', '??' => 'I', '??' => 'Ji', '??' => 'Ye', '??' => 'g', '??' => 'i', '??' => 'ji', '??' => 'ye',

        // Serbian
        '??' => 'dj', '??' => 'j', '??' => 'lj', '??' => 'nj', '??' => 'c', '??' => 'dz', '??' => 'Dj', '??' => 'j',
        '??' => 'Lj', '??' => 'Nj', '??' => 'C', '??' => 'Dz',

        // Russian
        '??' => '', '??' => '', '??' => 'A', '??' => 'B', '??' => 'C', '??' => 'Ch', '??' => 'D', '??' => 'E', '??' => 'E',
        '??' => 'E', '??' => 'F', '??' => 'G', '??' => 'H', '??' => 'I', '??' => 'J', '??' => 'Ja', '??' => 'Ju', '??' => 'K',
        '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O', '??' => 'P', '??' => 'R', '??' => 'S', '??' => 'Sh', '??' => 'Shch',
        '??' => 'T', '??' => 'U', '??' => 'V', '??' => 'Y', '??' => 'Z', '??' => 'Zh', '??' => '', '??' => '', '??' => 'a',
        '??' => 'b', '??' => 'c', '??' => 'ch', '??' => 'd', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'f', '??' => 'g',
        '??' => 'h', '??' => 'i', '??' => 'j', '??' => 'ja', '??' => 'ju', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n',
        '??' => 'o', '??' => 'p', '??' => 'r', '??' => 's', '??' => 'sh', '??' => 'shch', '??' => 't', '??' => 'u', '??' => 'v',
        '??' => 'y', '??' => 'z', '??' => 'zh',

        // Other
        '??' => '1', '??' => '2', '??' => '3', '??' => 'P'
    );

    
        /*//detect CJK encodign depending string
        $cjk_encoding = array('Big5','EUC-JP','EUC-KR','GB18030','GB2312','ISO 2022-JP','KS C 5861','Shift-JIS');
        //d($cjk_encoding);
    
        $encoding_detected = mb_detect_encoding($title,implode(',',$cjk_encoding));

        if (in_array($encoding_detected,$cjk_encoding))
            d('found'.$encoding_detected);SS
        else
            d('not found');*/

    public static function slugify($text, $separator = '-', array $modifier = array())
    {
        $normalized = strtolower(strtr($text, $modifier + self::$chars_map));
        $cleaned = preg_replace($unwantedChars = '/([^a-z0-9]|-)+/', $separator, $normalized);

        return trim($cleaned, $separator);
    }

    /**
     * Slugify for CJK characters. removes emojis and unwanted chars from texts
     * @param  string $text 
     * @return string
     * @see http://stackoverflow.com/a/12824140/514629
     */
    public static function cjk_slugify($text, $separator = '-') 
    {
        $clean_text = '';

        // default operations with string no matter the encoding
        $clean_text = mb_strtolower(trim($text));
        $clean_text = str_replace(array("'",'/',' ','&','+','_','.','=','???','???','???','???','???','???','???','?'),$separator,$clean_text);


        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, $separator, $clean_text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, $separator, $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, $separator, $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, $separator, $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, $separator, $clean_text);

        // remove duplicate -
        $clean_text = preg_replace('~-+~', $separator, $clean_text);

        // remove - at begining and end
        $clean_text = trim($clean_text,$separator);

        return $clean_text;
    }


} // End url
