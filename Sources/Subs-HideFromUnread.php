<?php

if (!defined('SMF'))
    die('Hacking attempt...');

//Admin Areas
function hidefromunread_add_admin_areas(&$adminAreas)
{
    global $txt;

	loadLanguage('HideFromUnread/HideFromUnread');

    $adminAreas['config']['areas'] += array(
        'hidefromunread' => array(
            'label' => $txt['hidefromunread_title'],
            'file' => 'ManageHideFromUnread.php',
            'function' => 'ManageHideFromUnread',
        ),
    );
}

function get_hidefromunread_data()
{
	global $sourcedir, $modSettings, $smcFunc;

	// Find all the boards this user is allowed to see.
    $request = $smcFunc['db_query']('', "
        SELECT b.id_cat, c.name AS cat_name, b.id_board, b.name, b.child_level
        FROM {db_prefix}boards AS b
            LEFT JOIN {db_prefix}categories AS c ON (c.id_cat = b.id_cat)");
    $categories = array();
    while ($row = $smcFunc['db_fetch_assoc']($request))
    {
        // This category hasn't been set up yet..
        if (!isset($categories[$row['id_cat']]))
            $categories[$row['id_cat']] = array(
                'id' => $row['id_cat'],
                'name' => $row['cat_name'],
                'boards' => array()
            );
        // Set this board up, and let the template know when it's a child.  (indent them..)
        $categories[$row['id_cat']]['boards'][$row['id_board']] = array(
            'id' => $row['id_board'],
            'name' => $row['name'],
            'child_level' => $row['child_level'],
        );
    }
    mysql_free_result($request);

    $hidden_boards = explode(",", $modSettings['kumnaa_hide_from_unread']);

    foreach ($hidden_boards AS $key=>$value) {
		$hidden_boards[$key] = trim($value);
    }

    $board_data = array();

    foreach ($categories AS $key=>$category) {
		$board_data[] = array('board_name'=> '<b>'. $category['name'] .'</b>', 'is_hidden' => '');    	
    	foreach ($category['boards'] AS $board) {
			$checked = in_array($board['id'], $hidden_boards) ? ' checked="checked"': '';
			$board_name = $board['name'];
			for ($i = 0; $i < $board['child_level']; $i++) {
				$board_name = '&#160;&#160;&#160;&#160;' . $board_name;
			}

    		$board_data[] = array('board_name'=> $board_name, 'is_hidden' => '<input type="checkbox" name="hideFromUnread[]" value="'. $board['id']. '"'. $checked. ' />');
    	}
    }

    mail('kumnaa@gmail.com', 'Debug', print_r($cat_tree, true));

	return $board_data;
}
?>