<fieldset style="width:70%;margin:auto">
    <legend>新增問卷</legend>
    <!-- table>tr*2>td*2 -->
    <table style="width:100%">
        <tr>
            <td>問卷名稱</td>
            <td>
                <input type="text" name="subject" id="subject" style="width:80%">
            </td>
        </tr>
        <tr>
            <td colspan='2'>
                <div id="options">
                    選項<input type="text" name="option[]" id=""  style="width:80%">
                    <button onclick="more()">更多</button>
                </div>
            </td>
        </tr>
    </table>
    <div class="ct">
        <button onclick="send()">新增</button>
        <button onclick="resetForm()">清空</button>
    </div>
</fieldset>
<script>
    function more(){
        let el=`<div>選項<input type="text" name="option[]" id="" style="width:80%"></div>`
        $("#options").before(el)
    }
    function send(){
        let subject=$("#subject").val()
        let options=$("input[name]").map((id,item)=>$(item).val()).get()
        console.log(subject,)


     }
    function resetForm(){
        $("input[type='text']").val("")
    }

</script>