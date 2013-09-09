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

class block_slider_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Block title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_slider'));
        $mform->setType('config_title', PARAM_TEXT);

        // Slide count.
        $countoptions = array();
        for ($i=1; $i<BLOCK_SLIDER_MAX_SLIDES+1; $i++) {
            $countoptions[$i] = $i;
        }
        $mform->addElement('select', 'config_slidecount', get_string('configslidecount', 'block_slider'), $countoptions);
        $mform->setType('config_slidecount', PARAM_INT);
        $mform->setDefault('config_slidecount', BLOCK_SLIDER_DEFAULT_SLIDECOUNT);

        // Timing.
        $timeoutoptions = array();
        for ($i=1; $i<BLOCK_SLIDER_MAX_TIMEOUT; $i++) {
            $timeoutoptions[$i] = $i;
        }
        $mform->addElement('select', 'config_timeout', get_string('configslidetimeout', 'block_slider'), $timeoutoptions);
        $mform->setType('config_timeout', PARAM_INT);
        $mform->setDefault('config_timeout', BLOCK_SLIDER_DEFAULT_TIMEOUT);

        // Slides.
        $slidecount = empty($this->block->config->slidecount) ? BLOCK_SLIDER_DEFAULT_SLIDECOUNT : $this->block->config->slidecount;
        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $this->block->context);
        for ($i=1; $i<$slidecount+1; $i++) {
            $mform->addElement('editor', "config_text[{$i}]", get_string('configslidex', 'block_slider', $i), null, $editoroptions);
        }

    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = array();
            for ($i=1;$i<$this->block->config->slidecount+1;$i++) {
                $text[$i] = $this->block->config->text[$i];
                $draftid_editor = file_get_submitted_draft_itemid("config_text[{$i}]");
                if (empty($text[$i])) {
                    $currenttext = '';
                } else {
                    $currenttext = $text[$i];
                }
                $defaults->config_text[$i]['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_slider', 'slidecontent', $i, array('subdirs'=>true), $currenttext);
                $defaults->config_text[$i]['itemid'] = $draftid_editor;
                $defaults->config_text[$i]['format'] = $this->block->config->format[$i];
            }
        } else {
            $text = '';
        }

        if (!$this->block->user_can_edit() && !empty($this->block->config->title)) {
            // If a title has been set but the user cannot edit it format it nicely
            $title = $this->block->config->title;
            $defaults->config_title = format_string($title, true, $this->page->context);
            // Remove the title from the config so that parent::set_data doesn't set it.
            unset($this->block->config->title);
        }

        // have to delete text here, otherwise parent::set_data will empty content
        // of editor
        unset($this->block->config->text);
        parent::set_data($defaults);
        // restore $text
        if (!isset($this->block->config)) {
            $this->block->config = new stdClass();
        }
        $this->block->config->text = $text;
        if (isset($title)) {
            // Reset the preserved title
            $this->block->config->title = $title;
        }
    }
}
