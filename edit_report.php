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
 * Add/edit reports
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$id = optional_param('id', 0, PARAM_INT);

$ctx = context_system::instance();

require_login();
require_capability('block/powerbi:addinstance', $ctx);

$PAGE->set_context($ctx);
$PAGE->set_url('/blocks/powerbi/report.php');
$PAGE->set_heading(new lang_string('pluginname', 'block_powerbi'));

$output = $PAGE->get_renderer('block_powerbi');
$form = new \block_powerbi\output\form\report();

if ($form->is_cancelled()) {
    redirect('/blocks/powerbi/report.php');
} else if ($data = $form->get_data()) {
    if ($DB->insert_record('block_powerbi_reports', $data)) {
        redirect(new moodle_url('/blocks/powerbi/report.php'), get_string('reportadded', 'block_powerbi'));
    }
}
if ($id) {
    $heading = get_string('editingreport', 'block_powerbi');
    $form->set_data($DB->get_record('block_powerbi_report', ['id' => $id]));
} else {
    $heading = get_string('addingreport', 'block_powerbi');
}

echo $output->header(),
     $output->heading($heading),
     $form->render(),
     $output->footer();
