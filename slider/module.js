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

M.block_slider = M.block_slider || {
    Y: null,

    config: {
        sliderid:'',
        timeout:''
    },

    init_slider: function(Y, args) {
        var module = this;

        // Save a reference to the Y instance (all of its dependencies included).
        this.Y = Y;

        // If defined, parse args into this module's config object.
        if (args) {
            var jargs = Y.JSON.parse(args);
            for (var a in jargs) {
                if (Y.Object.owns(jargs, a)) {
                    this.config[a] = jargs[a];
                }
            }
        }

        // check if required param sliderid is available
        if (!this.config.sliderid) {
            throw new Error('M.block_slider.init_slider()-> Required config \'sliderid\' not available.');
        }

        // check jQuery dependency and continue with setup
        if (typeof $ === 'undefined') {
            throw new Error('M.block_slider.init_slider()-> jQuery dependency required for this module to function.');
        }


        $('#'+module.config.sliderid).cycle({
            //fx: 'scrollHorz',
            timeout: module.config.timeout*1000,  // miliseconds
            pager: '#block-slider-nav-'+module.config.sliderid,
            prev: '#block-slider-nav-prev-'+module.config.sliderid,
            next: '#block-slider-nav-next-'+module.config.sliderid,
            sync: 0,
            pagerAnchorBuilder:function(idx,slide){return'<li><a href=\"#\">&nbsp;&nbsp;</a></li>';}
        });
    }
}

