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

defined('MOODLE_INTERNAL') || die;

/**
 * slider block renderer
 *
 */
class block_slider_renderer extends plugin_renderer_base {

    /**
     * Adds the slide divs to a container that the js lib can use
     *
     * @param string $sliderid unique identifier for the container
     * @param string div elements, containing html for the individual slides
     * @return string an html string
     */
    public function slider_container($sliderid, $slides) {
        global $CFG;

        $html = html_writer::tag('div', $slides, array('id' => $sliderid, 'class' => 'block-slider'));
        $html .= html_writer::start_tag('div', array('class' => 'block-slider-nav-container'));
        $previmg = html_writer::empty_tag('img', array('src' => $CFG->wwwroot.'/blocks/slider/images/prev.png'));
        $html .= html_writer::link('#', $this->output->pix_icon('prev', '', 'block_slider'),
            array('href' => '#', 'id' => 'block-slider-nav-prev-'.$sliderid, 'class' => 'block-slider-nav-prev'));
        $html .= html_writer::tag('div', '', array('class' => 'block-slider-nav', 'id' => 'block-slider-nav-'.$sliderid));
        $html .= html_writer::link('#', $this->output->pix_icon('next', '', 'block_slider'),
            array('href' => '#', 'id' => 'block-slider-nav-next-'.$sliderid, 'class' => 'block-slider-nav-next'));
        $html .= html_writer::end_tag('div');
        $html .= html_writer::tag('div', '', array('class' => 'clearer'));

        return $html;
    }
}
