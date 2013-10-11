<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_advrssfeed'),
            get_string('descconfig', 'block_advrssfeed')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'advrssfeed/Allow_HTML',
            get_string('labelallowhtml', 'block_advrssfeed'),
            get_string('descallowhtml', 'block_advrssfeed'),
            '0'
        ));