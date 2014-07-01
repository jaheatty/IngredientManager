<?php
/**
 * Class gatherer
 *
 * @author Anni S (jaheatty)
 * 
 */
class gatherer {
    /** @var string $_url */
    private $_url = '';

    /** @var string $_error */
    private $_error = '';

    /** @var string $_siteContent */
    private $_siteContent = '';

    /**
     * set the url
     *
     * @param $url
     */
    public function setURL($url) {
        $this->_url = $url;
    }

    /**
     * get the ingredient list
     *
     * @return array|bool
     */
    public function getList() {
        return $this->getIngredient();
    }

    /**
     * get the content of an website
     *
     * @return bool|string
     */
    private function getSiteContent() {


        if (empty($this->_url) === true || strstr($this->_url, 'http') === false) {
            $this->_error = 'URL Fail';
            return false;
        }

        $content = file_get_contents($this->_url);

        if (empty($content) === true) {
            return false;
        }
        $this->_siteContent = $content;
        return $content;
    }

    /**
     * get the ingredients from the site content
     *
     * @return array|bool
     */
    private function getIngredient()
    {
        $matches = array();
        $pattern = "~<tr class=\"ingredient\">.*<td class=\"amount\">(.*)</td>.*<td class=\"name\">(.*)</td>.*</tr>~Uis";
        preg_match_all($pattern, $this->getSiteContent(), $matches);

        $matches[1] = array_map("htmlspecialchars_decode", $matches[1]);
        $matches[2] = array_map("htmlspecialchars_decode", $matches[2]);
        $matches[2] = array_map("strip_tags", $matches[2]);
        $matches[1] = array_map("trim", $matches[1]);
        $matches[2] = array_map("trim", $matches[2]);

        foreach ($matches[2] as $key => $ingrediend) {
            $ingrediens[] = array(
                'ingredient'    => $ingrediend,
                'amount'        => str_replace("&nbsp;", " ", $matches[1][$key])
            );
        }
        if (empty($ingrediens) === false) {
            return $ingrediens;
        }
        return false;
    }
}
