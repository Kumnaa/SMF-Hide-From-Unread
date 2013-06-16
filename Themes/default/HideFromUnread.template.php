<?php
function template_hide_from_unread_boards()
{
    global $context, $settings, $options, $scripturl, $txt;

    echo '
    <div id="admincenter">
    <form name="creator" action="', $context['post_url'], '" method="post" accept-charset="', $context['character_set'], '">
        
            
                    <table border="0" cellspacing="0" cellpadding="4" width="100%">';

    // Is there a custom title?
    if (isset($context['settings_title']))
        echo '
        <div class="cat_bar">
<h3 class="catbg"> Hide Forums From Unread Settings </h3>
        </div>';

    //Hide boards option
    echo '  
                        <tr>
                            <td class="windowbg2" colspan="3">
                                <fieldset class="windowbg2" style="padding: 10px; margin-left: 5px; margin-right: 5px;">
                                        <strong>' . $txt['tp_select_boards'] . '</strong><br />
                                        <table id="searchBoardsExpand" width="100%" border="0" cellpadding="1" cellspacing="0" align="center" style="margin-top: 1ex;">';

            foreach($context['categories'] AS $categories) {
                $category_id = key($categories);
                                        echo '
                                                <tr>
                                                    <td>', $categories[$category_id]['name'], '</td>';
                foreach ($categories[$category_id]['boards'] as $board) {
                        echo '
                                                <tr>
                                                    <td>';

                    if (!empty($board) && empty($board['child_ids']))
                        echo '
                                                        <label for="brd', $board['id'], '" style="margin-left: ', $board['child_level'], 'ex;"><input type="checkbox" id="brd', $board['id'], '" name="brd[', $board['id'], ']" value="', $board['id'], '"', $board['is_moderator'] ? ' checked="checked"' : '', ' class="check" />', $board['name'], '</label>';
                    elseif (!empty($board))
                        echo '
                                                        <a href="javascript:void(0);" onclick="selectBoards([', implode(', ', $board['child_ids']), ']); return false;" style="text-decoration: underline;">', $board['name'], '</a>';

                    echo '
                                                    </td>';
                        echo '
                                                </tr>';
                }
            }

            echo '
                                        </table><br /><br />
                                    </fieldset> 
                                </td>
                            </tr>';
    echo '
                    </table>
                
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    </form></div>';
}
?>