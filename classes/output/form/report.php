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

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('reportname', 'block_powerbi'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('text', 'workspace_id', get_string('reportworkspaceid', 'block_powerbi'));
        $mform->setType('workspace_id', PARAM_TEXT);
        $mform->addRule('workspace_id', null, 'required', null, 'client');

        $mform->addElement('text', 'report_id', get_string('reportreportid', 'block_powerbi'));
        $mform->setType('report_id', PARAM_TEXT);
        $mform->addRule('report_id', null, 'required', null, 'client');

        $options = [];
        $values = [];
        foreach ($this->_customdata['cohorts'] as $c) {
            $options[$c->id] = $c->name;
            if (!is_null($c->reportcohort)) {
                $values[] = $c->id;
            }
        }

        $autocomplete = $mform->addElement(
            'autocomplete',
            'cohorts',
            get_string('cohorts', 'block_powerbi'),
            $options,
            ['multiple' => true]
        );
        $autocomplete->setSelected($values);

        $elements = [
            $mform->createElement('text', 'filtertable', '', ['size' => 12]),
            $mform->createElement('text', 'filterfield', '', ['size' => 12]),
            $mform->createElement('select', 'mdlfield', '', $this->filter_options()),
            $mform->createElement('checkbox', 'base64', get_string('applybase64', 'block_powerbi')),
        ];
        $filters = $mform->createElement('group', 'filters', get_string('filter', 'block_powerbi'), $elements);

        $rules = [
            'filters[filtertable]' => ['type' => PARAM_TEXT],
            'filters[filterfield]' => ['type' => PARAM_TEXT]
        ];
        $this->repeat_elements([$filters], 3, $rules,
            'filterscount', 'addfilters', 3, get_string('addfilters', 'block_powerbi'));

        $this->add_action_buttons();
    }

    protected function filter_options() {
        global $DB;

        $filters = [
            'id'          => 'id',
            'username'    => get_string('username'),
            'idnumber'    => get_string('idnumber'),
            'firstname'   => get_string('firstname'),
            'lastname'    => get_string('lastname'),
            'fullname'    => get_string('fullnameuser'),
            'email'       => get_string('email'),
            'phone1'      => get_string('phone1'),
            'phone2'      => get_string('phone2'),
            'institution' => get_string('institution'),
            'department'  => get_string('department'),
            'address'     => get_string('address'),
            'city'        => get_string('city'),
            'timezone'    => get_string('timezone'),
            'url'         => get_string('webpage'),
        ];

        if ($profilefields = $DB->get_records('user_info_field', [], 'sortorder ASC')) {
            foreach ($profilefields as $f) {
                $filters['profile_field_' . $f->shortname] = format_string($f->name);
            }
        }

        return $filters;
    }
}
