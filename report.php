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
 * List reports
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$ctx = context_system::instance();

require_login();
require_capability('block/powerbi:managereports', $ctx);

$str = get_config('block_powerbi', 'title');
$url = new moodle_url('/blocks/powerbi/report.php');

$PAGE->set_context($ctx);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);
$PAGE->set_title($str . ' - ' . $SITE->fullname);
$PAGE->set_heading($str);
$PAGE->navbar->add($str, $url);

$output = $PAGE->get_renderer('block_powerbi');

$table = new \block_powerbi\output\reports_table();

echo $output->header(),
     $output->heading(get_string('managereports', 'block_powerbi')),
     $output->render($table),
     $output->footer();
