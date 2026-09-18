<?php
GummRouter::connect('custom_portfolio_cat', '/work/%pagename%/%portfolio_cat%', array(
    'reverseRewrite' => array(
        // 'filter' => 'term_link',
    )
));
?>