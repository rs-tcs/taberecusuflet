<?php
class TermsLayoutElement extends GummLayoutElement {
    /**
     * @var string
     */
    protected $id = '1D7A252C-B231-410B-B642-64237CC33542';
    
    /**
     * @var string
     */
    public $group = 'custom';
    
    /**
     * @var array
     */
    protected $supports = array('title');
    
    /**
     * @return string
     */
    public function title() {
        return __('Terms', 'gummfw');
    }
    
    /**
     * @return array
     */
    protected function _fields() {
        return array();
    }
    
    /**
     * @param array $class
     * @return array
     */
    protected function beforeRenderElementClass($class) {
        $class[] = 'terms-scroll-layout';
        
        return $class;
    }
    
    /**
     * @return void
     */
    protected function _render($options) {
        global $post;
        $posts = query_posts(array(
            'post_type' => 'term',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
        $alphabetizedPosts = $this->alphabetizePosts($posts);
        
        echo '<div class="span12">';
        echo "\n\t\t" . '<ul class="terms-alphabet">' . "\n";

        $alphabet = range('a', 'z');
        foreach ($alphabet as $i) {
            $itemClass = array();
            // if ($i == 'a') $itemClass[] = 'selected';
            if (!isset($alphabetizedPosts[$i])) $itemClass[] = 'no-terms';
            
            echo "\t\t\t" . '<li class="' . implode(' ', $itemClass) . '"><a data-scrollto="' . $i . '" href="#">' . $i . '</a></li>' . "\n";
        }        
        echo "\t\t" . '</ul>' . "\n";
        echo '</div>';
?>
        <table class="table table-striped offers-table a-to-z-terms">
            <tbody>
                <?php
                    $lastLetter = null;
                    foreach ($posts as $post):
                    setup_postdata($post);
                    $letter = strtolower(substr($post->post_title, 0, 1));
                ?>
                <?php if ($lastLetter != $letter): ?>
                    <?php
                    $rowspan = 1;
                    if (isset($alphabetizedPosts[$letter])) $rowspan = count($alphabetizedPosts[$letter]);
                    ?>
                    <tr class="new-letter" data-scrolltotarget="<?php echo $letter; ?>">
                        <td class="first-letter" rowspan="<?php echo $rowspan; ?>"><?php echo $letter; ?></td>
                        <td class="poker-term"><p><?php the_title(); ?></p></td>
                        <td class="term-meaning"><?php the_content(); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="poker-term"><p><?php the_title(); ?></p></td>
                        <td class="term-meaning"><?php the_content(); ?></td>
                    </tr>
                <?php endif; ?>
                <?php
                    $lastLetter = $letter;
                    endforeach;
                ?>
            </tbody>
        </table>
<?php
        
        wp_reset_query();
    }
    
    private function alphabetizePosts($posts=array()) {
        $result = array();
        
        foreach ($posts as $post) {
            $letter = strtolower(substr($post->post_title, 0, 1));
            if (!isset($result[$letter])) $result[$letter] = array();
            
            $result[$letter][] = $post;
        }
        return $result;
    }
}
?>