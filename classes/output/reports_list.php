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
 * Reports list
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_powerbi\output;
defined('MOODLE_INTERNAL') || die();

use moodle_url;
use renderable;
use templatable;
use renderer_base;

require_once($CFG->dirroot.'/cohort/lib.php');

/**
 * Reports list renderable class.
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reports_list implements renderable, templatable {

    public $reports = [];

    /**
     * Constructor.
     */
    public function __construct() {
        global $DB, $USER;
        if (has_capability('moodle/site:config', \context_system::instance())) {
            $this->reports = $DB->get_records('block_powerbi_reports');
        } else {

            $cohorts = cohort_get_user_cohorts($USER->id);

            if (!empty($cohorts)) {

                $cohortids = array_map(function($c) {
                    return $c->id;
                }, $cohorts);

                list($sqlcohorts, $params) = $DB->get_in_or_equal($cohortids);

                $sql = "SELECT DISTINCT p.*
                          FROM {block_powerbi_reports} p
                          JOIN {block_powerbi_reports_cohort} c
                            ON (c.reportid = p.id)
                         WHERE c.cohortid {$sqlcohorts}";

                $this->reports = $DB->get_records_sql($sql, $params);
            }
        }
    }

    public function export_for_template(renderer_base $output) {
        return (object)[
            'reports' => array_values($this->reports),
            'hasreports' => !empty($this->reports),
            'viewreporturl' => (new moodle_url('/blocks/powerbi/view.php'))->out(),
        ];
    }
}
