<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Tue, 19 Jul 2011 09:07:26 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Delete link
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( ! $id ) die( "NO" );
    
    $query = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_document` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    
    if ( $numrows > 0 )
    {
        $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_document` WHERE `id`=" . $id;
        $db->sql_query( $sql );
    }
    
    die( "OK" );

}

$edit = $error = '';
// List product
$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
$my_head .= "<script type=\"text/javascript\">\n";
$my_head .= "Shadowbox.init({\n";
$my_head .= "});\n";
$my_head .= "</script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/easyTooltip.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\">\n";
$my_head .= "$(document).ready(function(){ $(\".cusnote\").easyTooltip(); });\n";
$my_head .= "</script>\n";

$from = $to = $type = 0;

$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_document` WHERE `id`!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

$listcats = nv_listcats( 0 );

if ( empty( $listcats ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
    exit();
}

$listdes = nv_listdes( 0 );

if ( empty( $listdes ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=departments&add=1" );
    exit();
}

$listtypes = nv_listtypes( $type,0 );

if ( empty( $listtypes ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=tyes&add=1" );
    exit();
}

$listsinger = nv_signerList( 0 );

if ( empty( $listsinger ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=singer&add" );
    exit();
}

$page_title = $lang_module['table'];

if ( $nv_Request->isset_request( "type", "get" ) )
{
    $type = $nv_Request->get_int( 'type', 'get', 0 );
    if ( ! $type or ! isset( $listtypes[$type] ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    
    $page_title = sprintf( $lang_module['cv_list_by_type'], $listtypes[$type]['title'] );
    $sql .= " AND `type`=" . $type;
    $base_url .= "&amp;type=" . $type;
}

if ( $nv_Request->isset_request( "catid", "get" ) )
{
    $catid = $nv_Request->get_int( 'catid', 'get', 0 );
    
    if ( ! $catid or ! isset( $listcats[$catid] ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    
    $page_title = sprintf( $lang_module['product_list_by_cat'], $listcats[$catid]['title'] );
    $sql .= " AND `catid`=" . $catid;
    $base_url .= "&amp;catid=" . $catid;
}
if ( $nv_Request->isset_request( "signer", "get" ) )
{
    $signer = $nv_Request->get_int( 'signer', 'get', 0 );
    
    if ( ! $signer or ! isset( $listsinger[$signer] ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    
    $page_title = sprintf( $lang_module['product_list_by_signer'], $listsinger[$signer]['name'] );
    $sql .= " AND `from_signer`=" . $signer;
    $base_url .= "&amp;signer=" . $signer;
}


if ( $nv_Request->isset_request( "from", "get" ) )
{
    $from = filter_text_input( 'from', 'post,get', '' );
    
    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $from, $m ) )
    {
        $from = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $from = 0;
    }
    
    if ( $from != 0 )
    {
        //die($year.'');
        
        $sql .= " AND `from_time` >= " . $from;
        $base_url .= "&amp;from =" . $from;
    }
}
if ( $nv_Request->isset_request( "to", "get" ) )
{
    $to = filter_text_input( 'to', 'post,get', '' );
    
    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $to, $m ) )
    {
        $to = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $to = 0;
    }
	if ( $to != 0 )
    {
        //die($year.'');
        
        $sql .= " AND `from_time` <= " . $to;
        $base_url .= "&amp;to=" . $to;
    }
}

$sql1 = "SELECT COUNT(*) " . $sql;

$result1 = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result1 );

if ( ! $all_page )
{
    $error = 'Không có dữ liệu như bạn tìm';
}

$sql .= " ORDER BY `from_time` DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$array = array();
$i = 0;

while ( $row = $db->sql_fetchrow( $query2 ) )
{
    $i = $i + 1;
    if ( $row['type'] == 0 )
    {
        $edit = "&come=1&id=" . $row['id'];
    }
    else if ( $row['type'] == 1 )
    {
        $edit = "&go=1&id=" . $row['id'];
    }
    else
    {
        $edit = "&inter=1&id=" . $row['id'];
    }
    
    if ( strlen( $row['content'] ) > 100 )
    {
        $content = nv_clean60( $row['content'], 100 );
    
    }
    else
    {
        $content = $row['content'];
    }
    $array[$row['id']] = array(  //
        'id' => ( int )$row['id'], //
		'stt' => $i, //
		'title' => $row['title'], //
		'code' => $row['code'], //
		'cat' => $listcats[$row['catid']]['title'], //
		'type' => $listtypes[$row['type']]['title'], //
    	'from_signer' =>$listsinger[$row['from_signer']]['name'],//
    	'link_singer' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;signer=" . $row['from_signer'], //	
		'content' => $content, //
		'link_type' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;type=" . $row['type'], //
		'link_cat' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;catid=" . $row['catid'], //		
		'from_time' => nv_date( 'd.m.Y', $row['from_time'] ), //	
		'status' => $arr_status[$row['status']]['name'], //
		'link_detail' => NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $row['alias'], 'edit' => $edit 
    );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TABLE_CAPTION', $lang_module['table'] );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );

if ( ! empty( $array ) )
{
    $a = 0;
    foreach ( $array as $row )
    {
        $xtpl->assign( 'CLASS', $a % 2 == 1 ? " class=\"second\"" : "" );
        $xtpl->assign( 'ROW', $row );
        
        $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;&op=add_document" . $row['edit'] );
        
        if ( ! empty( $row['images'] ) )
        {
            $xtpl->parse( 'main.row.img' );
        }
        
        $xtpl->parse( 'main.row' );
        $a ++;
    }
}
if ( $error != '' )
{
    $xtpl->assign( 'ERROR', $error );
    $xtpl->parse( 'main.error' );
}

foreach ( $listtypes as $types )
{
    $xtpl->assign( 'LISTTYPES', $types );
    $xtpl->parse( 'main.typeid' );
}

if ($from != 0)
{
	 $xtpl->assign( 'FROM', nv_date("d.n.Y",$from ));
}
if ($to != 0 )
{
	 $xtpl->assign( 'TO', nv_date("d.n.Y", $to ));
}

for ( $i = 1990; $i < 2101; $i ++ )
{
    $xtpl->assign( 'year', $i );
    if ( $i == nv_date( 'Y', NV_CURRENTTIME ) )
    {
        $xtpl->assign( 'selected', 'selected=selected' );
    }
    else
    {
        $xtpl->assign( 'selected', '' );
    }
    $xtpl->parse( 'main.year' );
}

if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>