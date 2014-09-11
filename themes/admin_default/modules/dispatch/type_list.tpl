<!-- BEGIN: main -->
<script type="text/javascript">
	var type_del_cofirm = "{LANG.type_del}";
</script>
<div style="margin-top:8px;">
    <a class="button1" href="{ADD_NEW_TYPE}"><span><span>{LANG.type_add}</span></span></a>
</div>
<div class="clear"></div>
<div id="users">
    <table class="tab1">
        <caption>{TABLE_CAPTION}</caption>
        <thead>
            <tr>
                <td>
                    {LANG.type_sort}
                </td>
                <td>
                    {LANG.type_name}
                </td>
                <td>
                    {LANG.type_parent}
                </td>
                <td style="width:20px;text-align:center">
                    {LANG.type_active}
                </td>
                <td style="width:100px;white-space:nowrap;text-align:center">
                    {LANG.feature}
                </td>
            </tr>
        </thead>
        <!-- BEGIN: row -->
        <tbody{ROW.class}>
            <tr>
                <td style="width:15px">
                    <select name="weight" id="weight{ROW.id}" onchange="nv_chang_type_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.pos}"{WEIGHT.selected}>{WEIGHT.pos}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td>
                    <strong><a href="{ROW.titlelink}">{ROW.title}</a></strong>{ROW.numsub}
                </td>
                <td>
                    {ROW.parentid}
                </td>
                <td style="width:20px;white-space:nowrap;text-align:center">
                    <input type="checkbox" name="active" id="change_status{ROW.id}" value="1"{ROW.status} onclick="nv_chang_type_status({ROW.id});" />
                </td>
                <td style="white-space:nowrap;text-align:center">
                    <span class="edit_icon"><a href="{EDIT_URL}">{GLANG.edit}</a></span>
                    &nbsp;&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_type_del({ROW.id});">{GLANG.delete}</a></span>
                </td>
            </tr>
        </tbody>
        <!-- END: row -->
    </table>
</div>
<!-- END: main -->
