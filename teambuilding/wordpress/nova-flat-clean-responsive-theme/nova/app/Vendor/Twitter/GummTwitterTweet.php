<?php
require_once 'twitteroauth/twitteroauth.php';

App::uses('Security', 'Lib/Utility');

class GummTwitterTweet {
    /**
     * @var int
     * 
     * Cache time in minutes
     */
    public $cacheTime = 5;
    
    /**
     * @var TwitterOAuth
     */
    private $_connection;
    
    
    public function getLatest($username, $limit=5) {
        $transName = 'gumm-twitter-latest-' . $username . '-' . $limit;
        
        if (false === ($twitterData = get_transient($transName) )) {
            $twitterData = $this->getConnection()->get(
                'statuses/user_timeline',
                array(
                    'screen_name'     => trim($username, '@'),
                    'count'           => $limit,
                    'exclude_replies' => true
                )
            );
            
            if ($twitterData) {
                set_transient($transName, $twitterData, 60 * $this->cacheTime);
            }
        }
        return $this->parseTweets($twitterData);
    }
    
    public function parseTweets($tweets) {
        $result = array();
        if ($tweets) {
            foreach ($tweets as $tweet) {
                $text = $tweet->text;
                $text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $text);
                $text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"https://\\2\" >\\2</a>", $text);
                $text = preg_replace("/@(\w+)/", "<a href=\"https://www.twitter.com/\\1\" class=\"twitter-atreply pretty-link\" >@\\1</a>", $text);
                $text = preg_replace("/#(\w+)/", "<a href=\"https://search.twitter.com/search?q=\\1\" >#\\1</a>", $text);
                // return $ret;
                // if ($replyTo = $tweet['in_reply_to_screen_name']) {
                //     $replyToLink = '<a href="https://twitter.com/' . $replyTo . '" class="twitter-atreply pretty-link"><s>@</s><strong>' . $replyTo . '</strong></a>';
                //     $text = str_replace('@' . $replyTo, $replyToLink, $text);
                // }
            
                $time = GummRegistry::get('Helper', 'Time')->timeAgoInWords($tweet->created_at);
            
                $result[] = array(
                    'text' => $text,
                    'time' => $time,
                    'user' => $tweet->user
                );
            }
        }
        
        return $result;
    }
    
    private function getConnection() {
        if (!$this->_connection) {
            $this->_connection = new TwitterOAuth(
                Security::cipher(base64_decode(Configure::read('Data.Twitter.consumerKey')), 'gummTwitterCypher'),
                Security::cipher(base64_decode(Configure::read('Data.Twitter.consumerSecret')), 'gummTwitterCypher'),
                Security::cipher(base64_decode(Configure::read('Data.Twitter.accessToken')), 'gummTwitterCypher'),
                Security::cipher(base64_decode(Configure::read('Data.Twitter.accessTokenSecret')), 'gummTwitterCypher')
            );
        }
        
        return $this->_connection;
    }
}
?>