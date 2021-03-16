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
 * Delete reports
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

require_login();

require_capability('block/powerbi:managereports', context_system::instance());

if (confirm_sesskey()) {
    if ($confirm) {
        $DB->delete_records('block_powerbi_reports', ['id' => $id]);
        redirect(new moodle_url('/blocks/powerbi/report.php'), get_string('reportdeleted', 'block_powerbi'));
    } else {
        $report = $DB->get_record('block_powerbi_reports', ['id' => $id]);
        $title = get_string('confirmdeletereport', 'block_powerbi');
        $PAGE->set_context(context_system::instance());
        $PAGE->set_pagelayout('standard');
        $PAGE->set_url(new moodle_url('/blocks/powerbi/delete_report.php'));
        $PAGE->set_title(new lang_string('confirm'));
        $PAGE->set_heading($report->name);
        $PAGE->navbar->add(new lang_string('managereports', 'block_powerbi'), new moodle_url('/blocks/powerbi/report.php'));
        $PAGE->navbar->add($report->name);
        echo $OUTPUT->header();
        $message = get_string('confirmdeletereport', 'block_powerbi');
        $optionsyes = ['id' => $id, 'sesskey' => sesskey(), 'confirm' => 1];
        $optionsno = [];
        $buttoncontinue = new single_button(new moodle_url('/blocks/powerbi/delete_report.php', $optionsyes), get_string('yes'), 'get');
        $buttoncancel   = new single_button(new moodle_url('/blocks/powerbi/report.php', $optionsno), get_string('no'), 'get');
        echo $OUTPUT->confirm($message, $buttoncontinue, $buttoncancel);
        echo $OUTPUT->footer();
    }
}
