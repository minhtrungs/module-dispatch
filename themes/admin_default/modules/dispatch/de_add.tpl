<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 780px;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
        <tbody class="second">
            <tr>
                <td>
                    {LANG.de_name}(<span style="color:red">*</span>)
                </td>
                <td>
                    <input class="txt" value="{DATA.title}" name="title" id="title" style="width:300px" maxlength="100" />
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.alias}
                </td>
                <td>
                    <input class="txt" value="{DATA.alias}" name="alias" id="alias" style="width:300px" maxlength="100" />
                </td>
            </tr>
        </tbody>        
        <tbody class="second">
            <tr>
                <td>
                    {LANG.introduction}
                </td>
                <td>
                	<textarea rows="4" cols="42" name="introduction">{DATA.introduction}</textarea>                    
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td>
                    {LANG.head}
                </td>
                <td>
                    <input class="txt" value="{DATA.head}" name="head" id="head" style="width:300px" maxlength="100" />
                </td>
            </tr>
        </tbody>
        <tbody class="second">
            <tr>
                <td>
                    {LANG.de_parent}
                </td>
                <td>
                    <select name="parentid">
                        <!-- BEGIN: parentid -->
                        <option value="{LISTCATS.id}"{LISTCATS.selected}>{LISTCATS.name}</option>
                        <!-- END: parentid -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="{LANG.save}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<!-- END: main -->
