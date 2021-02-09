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
 * Reports form.
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_powerbi\output\form;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use moodleform;

/**
 * Reports form class.
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class report extends moodleform {

    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('reportname', 'block_powerbi'));
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'workspace_id', get_string('reportworkspaceid', 'block_powerbi'));
        $mform->setType('workspace_id', PARAM_TEXT);

        $mform->addElement('text', 'report_id', get_string('reportreportid', 'block_powerbi'));
        $mform->setType('report_id', PARAM_TEXT);

        $this->add_action_buttons();
    }
}
