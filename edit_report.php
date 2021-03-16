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
require_once($CFG->dirroot.'/cohort/lib.php');

$id = optional_param('id', 0, PARAM_INT);

$ctx = context_system::instance();

require_login();
require_capability('block/powerbi:addinstance', $ctx);

if ($id) {
    $title = new lang_string('editingreport', 'block_powerbi');
    $heading = get_string('editingreport', 'block_powerbi');
} else {
    $title = new lang_string('addingreport', 'block_powerbi');
    $heading = get_string('addingreport', 'block_powerbi');
}

$url = new moodle_url('/blocks/powerbi/edit_report.php');

$PAGE->set_context($ctx);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading(new lang_string('pluginname', 'block_powerbi'));

$PAGE->navbar->add(new lang_string('managereports', 'block_powerbi'), new moodle_url('/blocks/powerbi/report.php'));
$PAGE->navbar->add($title);

$output = $PAGE->get_renderer('block_powerbi');

$cohortssql =
    'SELECT c.id, c.name, rc.id as reportcohort
       FROM {cohort} c
  LEFT JOIN {block_powerbi_reports_cohort} rc
         ON c.id = rc.cohortid AND rc.reportid = ?';
$cohorts = $DB->get_records_sql($cohortssql, [$id]);

$form = new \block_powerbi\output\form\report($url->out(false), ['cohorts' => $cohorts]);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/blocks/powerbi/report.php'));
} else if ($data = $form->get_data()) {
    if (empty($data->id)) {
        $reportid = $DB->insert_record('block_powerbi_reports', $data);
        $str = get_string('reportadded', 'block_powerbi');
    } else {
        $reportid = $data->id;
        $DB->delete_records('block_powerbi_reports_cohort', ['reportid' => $reportid]);
        $DB->delete_records('block_powerbi_reports_filter', ['reportid' => $reportid]);
        $DB->update_record('block_powerbi_reports', $data);
        $str = get_string('reportupdated', 'block_powerbi');
    }
    $reportcohort = (object)['reportid' => $reportid];
    if (isset($data->cohorts)) {
        foreach ($data->cohorts as $cohortid) {
            $reportcohort->cohortid = $cohortid;
            $DB->insert_record_raw('block_powerbi_reports_cohort', $reportcohort);
        }
    }
    $reportfilter = (object)['reportid' => $reportid];
    if (isset($data->filters)) {
        foreach ($data->filters as $filter) {
            $reportfilter->filtertable = $filter['filtertable'];
            $reportfilter->filterfield = $filter['filterfield'];
            $reportfilter->mdlfield = $filter['mdlfield'];
            $reportfilter->base64 = empty($filter['base64']) ? 0 : 1;
            $DB->insert_record_raw('block_powerbi_reports_filter', $reportfilter);
        }
    }
    redirect(new moodle_url('/blocks/powerbi/report.php'), $str);
}
if ($id) {
    $report = $DB->get_record('block_powerbi_reports', ['id' => $id]);
    $filters = $DB->get_records('block_powerbi_reports_filter', ['reportid' => $id]);
    $report->filters = [];
    foreach ($filters as $f) {
        $report->filters[] = [
            'filtertable' => $f->filtertable,
            'filterfield' => $f->filterfield,
            'mdlfield' => $f->mdlfield,
            'base64' => $f->base64
        ];
    }
    $form->set_data($report);
}

echo $output->header(),
     $output->heading($heading),
     $form->render(),
     $output->footer();
