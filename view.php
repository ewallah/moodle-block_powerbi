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

$id = required_param('id', PARAM_INT);

$ctx = context_system::instance();

require_login();
require_capability('block/powerbi:viewreports', $ctx);

$report = $DB->get_record('block_powerbi_reports', ['id' => $id]);
$report->filters = $DB->get_records('block_powerbi_reports_filter', ['reportid' => $report->id]);

$str = new lang_string('pluginname', 'block_powerbi');

$PAGE->set_context($ctx);
$PAGE->set_url('/blocks/powerbi/report.php');
$PAGE->set_heading($str);
$PAGE->set_title($str);
if (has_capability('block/powerbi:managereports', $ctx)) {
    $PAGE->navbar->add(new lang_string('managereports', 'block_powerbi'), new moodle_url('/blocks/powerbi/report.php'));
}
$PAGE->navbar->add($str);

$output = $PAGE->get_renderer('block_powerbi');
$report = new \block_powerbi\output\embedded_report($report, $PAGE);

echo $output->header(),
     $output->render($report),
     $output->footer();
