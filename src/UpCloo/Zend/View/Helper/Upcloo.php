<?php 
class UpCloo_Zend_View_Helper_Upcloo
    extends Zend_View_Helper_Abstract
{
    public function upcloo($id, $visitekey = false)
    {
        $manager = UpCloo_Manager::getInstance();
        
        $links = $manager->get($id, $visitekey);
        
        $html = '';
        if (is_array($links) && count($links) > 0) {
            $html = '<ul class="upcloo-correlate">';
            foreach ($links as $link) {
                $html .= "<li><a href='{$link["url"]}'>{$link["title"]}</a></li>";
            }
            $html .= '</ul>';
        }
        
        return $html;
    }
}