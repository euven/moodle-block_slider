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
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package contrib
 * @subpackage block_slider
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('BLOCK_SLIDER_MAX_SLIDES', 10);
define('BLOCK_SLIDER_DEFAULT_SLIDECOUNT', 5);
define('BLOCK_SLIDER_MAX_TIMEOUT', 90);  // seconds
define('BLOCK_SLIDER_DEFAULT_TIMEOUT', 8);  // seconds

class block_slider extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_slider');
    }

    function has_config() {
        return false;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        global $CFG;

        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('newsliderblock', 'block_slider'));
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $CFG;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $slidecount = !empty($this->config->slidecount) ? $this->config->slidecount : BLOCK_SLIDER_DEFAULT_SLIDECOUNT;
        $timeout = !empty($this->config->timeout) ? $this->config->timeout : BLOCK_SLIDER_DEFAULT_TIMEOUT;

        // Initialise js.
        $this->page->requires->jquery();
        $this->page->requires->js('/blocks/slider/jquery.cycle.all.js');
        $args = array('args'=>'{"sliderid":'.$this->instance->id.', "timeout":'.$timeout.'}');
        $jsmodule = array(
            'name' => 'block_slider',
            'fullpath' => '/blocks/slider/module.js',
            'requires' => array('json')
        );
        $this->page->requires->js_init_call('M.block_slider.init_slider', $args, false, $jsmodule);
        unset($args, $jsmodule);

        $filteropt = new stdClass();
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // Fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        // Build content.
        $this->content = new stdClass();
        $this->content->footer = '';
        $this->content->text = '';
        for ($i = 1; $i < $slidecount+1; ++$i) {
            if (!empty($this->config->text[$i])) {
                $format = empty($this->config->format[$i]) ? FORMAT_HTML : $this->config->format;
                // Rewrite urls in text.
                $this->config->text[$i] = file_rewrite_pluginfile_urls($this->config->text[$i], 'pluginfile.php',
                    $this->context->id, 'block_slider', 'slidecontent', $i);
                // Add text.
                $this->content->text .=  html_writer::tag('div', format_text($this->config->text[$i], $format, $filteropt));
            }
        }
        if (!empty($this->content->text)) {
            $blockrenderer = $this->page->get_renderer('block_slider');
            $this->content->text = $blockrenderer->slider_container($this->instance->id, $this->content->text);
        }

        unset($filteropt, $blockrenderer);

        return $this->content;
    }

    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        for ($i=1; $i<$config->slidecount+1; $i++) {
            if (empty($data->text[$i])) {
                $config->text[$i] = '';  // for new slides
                $config->format[$i] = FORMAT_HTML;
            }
            // Move embedded files into a proper filearea and adjust HTML links to match
            $config->text[$i] = file_save_draft_area_files($data->text[$i]['itemid'], $this->context->id, 'block_slider', 'slidecontent', $i, array('subdirs'=>true), $data->text[$i]['text']);
            $config->format[$i] = $data->text[$i]['format'];
        }

        parent::instance_config_save($config, $nolongerused);
    }

    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_slider');
        return true;
    }

    function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        //find out if this block is on the profile page
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            } else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }
}
?>
