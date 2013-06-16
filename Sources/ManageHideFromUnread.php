<?php
if (!defined('SMF'))
    die('Hacking attempt...');

function ManageHideFromUnread()
{
    global $context, $txt, $scripturl, $modSettings, $settings, $sourcedir;
    
    // You need to be an admin to edit settings!
    isAllowedTo('admin_forum');

    if ($_POST && isset($_POST['hideFromUnread'])) {
    	if (is_array($_POST['hideFromUnread']) && count($_POST['hideFromUnread']) > 0) {
    		$checked_boards = array();
    		foreach ($_POST['hideFromUnread'] AS $value) {
    			if (is_numeric($value)) {
    				$checked_boards[] = $value;
    			}
    		}

    		updateSettings(array('kumnaa_hide_from_unread' => implode(',', $checked_boards)));
    	}
    }

	$list_options = array(
		'id' => 'list_hide_from_unread',
		'title' => $txt['hidefromunread_title'],
		'base_href' => $scripturl . '?action=admin;area=hidefromunread;' . $context['session_var'] . '=' . $context['session_id'],
		'get_items' => array(
			'function' => 'get_hidefromunread_data',
		),
		'no_items_label' => $txt['hidefromunread_no_boards'],
		'columns' => array(
			'board_name' => array(
				'header' => array(
					'value' => $txt['hidefromunread_board_name'],
				),
				'data' => array(
					'db' => 'board_name',
				),
				'sort' =>  array(
					'default' => 'board_name',
					'reverse' => 'board_name DESC',
				),
			),
			'hide' => array(
				'header' => array(
					'value' => $txt['hidefromunread_is_hidden'],
				),
				'data' => array(
					'db' => 'is_hidden',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=hidefromunread;' . $context['session_var'] . '=' . $context['session_id'],
			'name' => 'hidefromunread',
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '<input type="submit" value="'. $txt['hidefromunread_save'] . '" />'
			),
		),
	);
	$context['default_list'] = 'list_hide_from_unread';

    require_once($sourcedir . '/Subs-List.php');
	createList($list_options);

    $context['page_title'] = $txt['hidefromunread_title'];
	$context['sub_template'] = 'show_list';
}

?>