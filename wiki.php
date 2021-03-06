<?php
# MantisBT - a php based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

	/**
	 * @package MantisBT
	 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	 * @copyright Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	 * @link http://www.mantisbt.org
	 */
	 /**
	  * MantisBT Core API's
	  */
	require_once( 'core.php' );

	require_once( 'wiki_api.php' );

	$f_id = gpc_get_int( 'id' );
	$f_type = gpc_get_string( 'type', 'issue' );

	if ( $f_type == 'project' ) {
		if ( $f_id !== 0 ) {
			project_ensure_exists( $f_id );
		}

		$t_url = wiki_link_project( $f_id );
	} else {
		bug_ensure_exists( $f_id );
		$t_url = wiki_link_bug( $f_id );
        
       // skynin
       // http://mantisbt.dev/wiki/doku.php?id=dev:Desktop+Second:issue:1
       $t_url = str_replace(':issue:', ':task:', $t_url);
	}

	print_header_redirect( $t_url, true, false, true );
