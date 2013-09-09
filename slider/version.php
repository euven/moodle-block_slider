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
 * The Slider block allows for the creation of multiple HTML-rich "slides".
 * The content of these "slides" is then transitioned between upon block display.
 *
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package contrib
 * @subpackage block_slider
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2013090500;       // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2013051400;       // Requires this Moodle version - 2.5
$plugin->cron = 0;                    // Period for cron to check this module (secs)
$plugin->component = 'block_slider'; // To check on upgrade, that module sits in correct place
$plugin->maturity = MATURITY_RC;
$plugin->release = 'Version for Moodle 2.5 or greater';
