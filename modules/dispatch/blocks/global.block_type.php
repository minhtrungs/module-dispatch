<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_type_blocks' ) )
{

    function nv_block_config_type_blocks ( $module, $data_block, $lang_block )
    {
        global $db, $language_array, $module_array_cat, $module_file;
        
        
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['catid'] . "</td>";
        $html .= "	<td><select name=\"config_type\">\n";
        $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module . "_type` WHERE parentid = 0 ORDER BY `weight` ASC";
        $list = nv_db_cache( $sql, 'id', $module );
        foreach ( $list as $l )
        {
	        $xtitle_i = "";
	        if ( $l['parentid'] > 0 )
	        {
	            for ( $i = 1; $i <= $l['lev']; $i ++ )
	            {
	                $xtitle_i .= "----";
	            }
	        }
            $sel = ( $data_block['id'] == $l['id'] ) ? ' selected' : '';
            $html .= "<option value=\"" . $l['id'] . "\" " . $sel . ">" . $xtitle_i.$l['title'] . "</option>\n";
        }
        $html .= "	</select></td>\n";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['numrow'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
        $html .= "<td>";
        return $html;
    }

    function nv_block_config_type_blocks_submit ( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['type'] = $nv_Request->get_int( 'config_type', 'post', 0 );
        $return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
        return $return;
    }

    function nv_type_blocks ( $block_config )
    {
        global $module_data, $module_name, $module_file, $global_array_cat, $lang_module, $my_head, $db , $module_info;
        $module = $block_config['module'];
        
        $xtpl = new XTemplate( "block_hits.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module );
        $xtpl->assign( 'BASESITE', NV_BASE_SITEURL );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'module', $module );
        $a_t1 = array();
        $a_t = array();                  
   		$query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module . "_type` WHERE `status`=1 AND (`id`=" .$block_config['type']. " OR `parentid`= ".$block_config['type'].")" ;
    	$re = $db->sql_query( $query );
    	while ($row = $db -> sql_fetchrow($re))
    	{
    		$a_t1[] = $row['id'];
    	}
    	$query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module . "_type` WHERE `id`=" .$block_config['type'] ." OR `parentid` IN (".implode(',', $a_t1).")";
    	$re = $db->sql_query( $query );
    	while ($row = $db -> sql_fetchrow($re))
    	{
    		$a_t[] = $row['id'];
    	}
    	
        $sql = "SELECT id, alias, title,from_time, code, who_view, groups_view,file FROM `" . NV_PREFIXLANG . "_" . $module . "_document` WHERE `type` IN (".implode(',', $a_t).") ORDER BY `date_iss` DESC, `id` DESC LIMIT 0 ," . $block_config['numrow'];
        
        $result = $db->sql_query( $sql );
        $chk_topview = $db->sql_numrows( $result );
       	
        if ( $chk_topview )
        {
            
            while ( $row = $db->sql_fetchrow( $result ) )
            {
            	
            	if ( nv_set_allow( $row['who_view'], $row['groups_view'] ))
    			{
	                $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'];
			    	
	                if (nv_date('d.m.Y',$row['from_time'])== nv_date('d.m.Y',NV_CURRENTTIME))
			    	{
			    		$row['title'] = $row['title']."<img src=\"".NV_BASE_SITEURL."themes/".$module_info['template']."/images/".$module_file."/new.gif\">";
			    	}
    				$fileupload = explode( ",", $row['file'] );
    				
                    $i = 0;
                    foreach ( $fileupload as $f )
                    {
                        $i = $i + 1;
                        $xtpl->assign( 'FILEUPLOAD', $f );
                        $xtpl->assign( 'i', $i );
                        $xtpl->parse( 'main.topviews.loop.loop1' );
                    }
	                
	                $xtpl->assign( 'topviews', $row );
	                $xtpl->parse( 'main.topviews.loop' );
    			}
            }
            $xtpl->parse( 'main.topviews' );
            
        }
        //print_r($row);die();
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }

}

if ( defined( 'NV_SYSTEM' ) )
{
    $content = nv_type_blocks( $block_config );
}

?>