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
 * Embedded report
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_powerbi\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/lib/filelib.php");

use moodle_url;
use renderable;
use templatable;
use renderer_base;

/**
 * Embedded report renderable class.
 *
 * @package   block_powerbi
 * @copyright 2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class embedded_report implements renderable, templatable {

    public $token = '';

    /**
     * Constructor.
     */
    public function __construct(\stdClass $report) {
        global $CFG;

        if (($clientid = get_config('block_powerbi', 'clientid')) &&
            ($clientsecret = get_config('block_powerbi', 'clientsecret'))) {


            $curl = new \curl();
            $curl->setHeader('Accept: application/json');
            $curl->setHeader('Content-Type: application/x-www-form-urlencoded');
            $data = json_encode(
                (object)[
                    'grant_type' => 'client_credentials',
                    'scope' => 'openid',
                    'resource' => 'https://analysis.windows.net/powerbi/api',
                    'client_id' => $clientid,
                    'client_secret' => $clientsecret,
                ]
            );
            $url = 'https://login.microsoftonline.com/IDENTIFICADORDAEMPRESA/oauth2/token';
            $firstresponse = ($curl->post($url, $data));
            var_dump($curl->get_raw_response());

            $decodedresponse = json_decode($firstresponse);
            $curl = new \curl();
            $curl->setHeader('Authorization: Bearer '. $decodedresponse->access_token);
            $curl->setHeader('Content-type: application/json');
            $embeddata = json_encode(
                (object)[
                  'datasets' => [(object)['id' => $report->dataset_id]],
                  'reports' => [(object)['id' => $report->report_id]],
                  'targetWorkspaces' => [(object)['id' => $report->workspace_id]],
                ]
            );
            $url = 'https://api.powerbi.com/v1.0/myorg/GenerateToken';
            $this->token = $curl->post($url, $embeddata);
            $this->reportid = $report->report_id;
            $this->groupid = $report->dataset_id;
        }
    }

    public function export_for_template(renderer_base $output) {
        return (object)[
            'token' => $this->token,
            'managereportsurl' => (new moodle_url('/blocks/powerbi/report.php'))->out(),
        ];
    }
}
