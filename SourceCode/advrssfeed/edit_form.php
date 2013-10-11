<?php

require_once($CFG->libdir . '/simplepie/moodle_simplepie.php');

class block_advrssfeed_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blockstring', 'block_advrssfeed'));
        $mform->setDefault('config_title', 'TapChiLapTrinh.vn');
        $mform->addRule('config_title', null, 'maxlength', 50, 'client');
        $mform->addRule('config_title', null, 'required', null, 'client');
        $mform->setType('config_title', PARAM_MULTILANG);
        
        //input text Url
        $mform->addElement('text', 'config_feedurl', get_string('feedurl', 'block_advrssfeed'));
        $mform->setType('config_feedurl', PARAM_URL);
        $mform->addRule('config_feedurl', null, 'required');
        if (!empty($CFG->block_advrssfeed_feedurl)) {
            $mform->setDefault('config_feedurl', $CFG->block_advrssfeed_feedurl);
        } else {
            $mform->setDefault('config_feedurl', 'http://tapchilaptrinh.vn/feed');
        }
        //input text maxentries
        $mform->addElement('text', 'config_maxentries', get_string('shownumentrieslable', 'block_advrssfeed'), array('size' => 5));
        $mform->setType('config_maxentries', PARAM_INTEGER);
        $mform->addRule('config_maxentries', null, 'numeric', null, 'client');
        $mform->addRule('config_maxentries', null, 'nonzero', null, 'client');
        $mform->addRule('config_maxentries', null, 'required', null, 'client');
        $mform->addRule('config_maxentries', null, 'maxlength', 2, 'client');
        if (!empty($CFG->block_avdrssfeed_maxentries)) {
            $mform->setDefault('config_maxentries', $CFG->block_avdrssfeed_maxentries);
        } else {
            $mform->setDefault('config_maxentries', 5);
        }
        //input text maxleng Title
        $mform->addElement('text', 'config_maxtitle', get_string('maxtitle', 'block_advrssfeed'), array('size' => 5));
        $mform->setType('config_maxtitle', PARAM_INTEGER);
        $mform->addRule('config_maxtitle', null, 'numeric', null, 'client');
        $mform->addRule('config_maxtitle', null, 'nonzero', null, 'client');
        $mform->addRule('config_maxtitle', null, 'required', null, 'client');
        $mform->addRule('config_maxtitle', null, 'maxlength', 2, 'client');
        if (!empty($CFG->block_advrssfeed_maxtitle)) {
            $mform->setDefault('config_maxtitle', $CFG->block_advrssfeed_maxtitle);
        } else {
            $mform->setDefault('config_maxtitle', 50);
        }
        //input text maxleng description
        $mform->addElement('text', 'config_maxdescription', get_string('maxdescription', 'block_advrssfeed'), array('size' => 5));
        $mform->setType('config_maxdescription', PARAM_INTEGER);
        $mform->addRule('config_maxdescription', null, 'numeric', null, 'client');
        $mform->addRule('config_maxdescription', null, 'numeric', null, 'client');
        $mform->addRule('config_maxdescription', null, 'nonzero', null, 'client');
        $mform->addRule('config_maxdescription', null, 'required', null, 'client');
        $mform->addRule('config_maxdescription', null, 'maxlength', 3, 'client');
        if (!empty($CFG->block_advrssfeed_maxtitle)) {
            $mform->setDefault('config_maxdescription', $CFG->block_advrssfeed_maxtitle);
        } else {
            $mform->setDefault('config_maxdescription', 100);
        }
        //display description
        $optionsSelect = array(
            'No' => 'No',
            'Only Content' => 'Only Content',
            'Yes' => 'Content and Image',
        );
        $selectOptionDisplay = $mform->addElement('select', 'config_displayDescription', get_string('displayDescription', 'block_advrssfeed'), $optionsSelect);
        $mform->setType('config_displayDescription', PARAM_MULTILANG);
        $selectOptionDisplay->setSelected('Only Content');
        
    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $rss = new moodle_simplepie();
        // set timeout for longer than normal to try and grab the feed
        $rss->set_timeout(10);
        $rss->set_feed_url($data['config_feedurl']);
        $rss->set_autodiscovery_cache_duration(0);
        $rss->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
        $rss->init();
        return $errors;
    }

    public function specialization() {

        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        } else {
            $this->config->title = 'Default title ...';
        }
        if (empty($this->config->text)) {
            $this->config->text = 'Default text ...';
        }
    }

}