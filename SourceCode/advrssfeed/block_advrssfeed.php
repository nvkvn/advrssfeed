<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 
 
/**
 * A block which displays Advanced RSS Feed
 *
 * This block can display RSS Feed with some options. 
 *
 * @package    advrssfeed
 * @category   block
 * @copyright  2013 ScrumLab
 * @email	scrumlab@fpt.aptech.ac.vn
 * @created	Sep-2013
  @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
class block_advrssfeed extends block_base {

    public function init() {
        $this->title = get_string('advrssfeed', 'block_advrssfeed');
    }

    function specialization() {
        // At this point, $this->instance and $this->config are available
        // for use. We can now change the title to whatever we want.
        if (!empty($this->config) && !empty($this->config->title)) {
            // There is a customized block title, display it
            $this->title = $this->config->title;
        } else {
            // No customized block title, use localized remote news feed string
            $this->title = get_string('advrssfeed', 'block_advrssfeed');
        }
    }

    //Allow multiple block on moodle
    function instance_allow_multiple() {
        return true;
    }

    public function get_content() {
        //set Display Option
        $optionDisplayDes = "Only Content";
        if (!empty($this->config->displayDescription)) {
            $optionDisplayDes = $this->config->displayDescription;
        }

        //set max entries
        $maxentries = 5;
        if (!empty($this->config->maxentries)) {
            $maxentries = $this->config->maxentries;
        }

        if ($maxentries < 0) {
            $maxentries = 5;
        }
        //set max title
        $maxtitle = 40;
        if (!empty($this->config->maxtitle)) {
            $maxtitle = $this->config->maxtitle;
        }
        if($maxtitle < 5)
        {
            $maxtitle = 10;
        }

        //set max description
        $maxdescription = 100;
        if (!empty($this->config->maxdescription)) {
            $maxdescription = $this->config->maxdescription;
        }
        if ($maxdescription < 15) {
            $maxdescription = 50;
        }

		//set rss url
        $feedurl = 'http://tapchilaptrinh.vn/feed/';
        if (!empty($this->config->feedurl)) {
            $feedurl = $this->config->feedurl;
        }

        if ($this->content !== null) {
            return $this->content;
        }
		
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        $output = $this->read_feed($feedurl, $maxentries, $maxtitle, $maxdescription, $optionDisplayDes);
        $this->content->text = $output;
        return $this->content;
    }

    function read_feed($feedurl, $maxentries, $maxtitle, $maxdescription, $optionDisplayDes) {
        global $CFG;
        require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');
        $feed = new SimplePie();
        $feed->set_feed_url($feedurl);
        $feed->init();
        $feed->handle_content_type();
        if (!empty($feed->preferredtitle)) {
            $feedtitle = $feed->preferredtitle;
        } else {
            $feedtitle = $feed->get_title();
        }
		
        $content ='<div><ul class="list">';
        foreach ($feed->get_items(0, $maxentries) as $item) {
            $content.='<li class="hover">';
            $title = $this->slipt_text($item->get_title(), $maxtitle);
            $titletooltip = str_replace("\"", "''", $item->get_title());
            
			$content.= '<div class="rsslink"><a title="' . $titletooltip . '" href="' . $item->get_link() . '" target="_blank">' . $title . '</a></div>';
            $description = $this->slipt_text($this->split_description($item->get_description(), "No"), $maxdescription);
			
            switch ($optionDisplayDes) {
                case "Only Content":
                    $content.= '<div class="rssdescription">' . $description . '</div>';
                    break;
                case "Yes":
                    $image = $this->split_description($item->get_description(), "Yes");
                    if (strcmp($image, "NoImage") == 0) {
                        $content.= '<div class="rssdescription">' . $description . '</div>';
                    } else {
                        $content.= '<div class="rssdescription"><a target="_blank" href="' . $item->get_link() . '"><img class = "img" src="' . $image . '"/></a>' . $description . '</div>';
                    }
                    break;
            }
            $content.='</li>';
        }
        $content.='</ul></div>';

        return $content;
    }

    //split descriptin and image
    function split_description($urlText, $chooseImage, $charset = 'UTF-8') {
        $urlText = html_entity_decode($urlText, ENT_QUOTES, $charset);
        if (strcmp($chooseImage, "Yes") == 0) {
            $array = explode("src=\"", $urlText);
            $image_url = "NoImage";
            if (count($array) >= 2) {
                $array1 = explode("\"", $array[1]);
                $image_url = $array1[0];
            }
            return $image_url;
        }
        $description = strip_tags($urlText);
        return $description;
    }

    //slipt title 
    function slipt_text($text, $maxlenght, $charset = 'UTF-8') {
        $text = html_entity_decode($text, ENT_QUOTES, $charset);
        if (mb_strlen($text, $charset) > $maxlenght) {
            $arr = explode(' ', $text);
            $text = mb_substr($text, 0, $maxlenght, $charset);
            $arrRes = explode(' ', $text);
            $last = $arr[count($arrRes) - 1];
            unset($arr);
            if (strcasecmp($arrRes[count($arrRes) - 1], $last)) {
                unset($arrRes[count($arrRes) - 1]);
            }
            return implode(' ', $arrRes) . " ...";
        }
        return $text;
    }

}
