<div>
    目前位置：首頁 > 分類網誌 > <span id='type'>健康新知</span>
</div>
<style>
    /* 一樣的效果,可以用[,]逗號段開,並且寫在一起 */

    .type, .list-item{
        display: block;
        margin:10px 0px
    }
</style>

<!-- 行內標籤: a .input -->
<!-- 盒子標籤: div ... -->
<!-- 垂直對齊: vertical-align: top; -->

<fieldset style='width:150px; display: inline-block;vertical-align: top;'>
    <legend>分類網誌</legend>
    <a href="#" class='type' data-type='1'>健康新知</a>
    <a href="#" class='type' data-type='2'>菸害防制</a>
    <a href="#" class='type' data-type='3'>癌症防治</a>
    <a href="#" class='type' data-type='4'>慢性病防治</a>
</fieldset>

<fieldset style='width:500px; display: inline-block;'>
    <legend>文章列表</legend>
    <div id="postList"></div>
</fieldset>

<script>
    // 這邊用jQ寫的
    // 我點這個type(a標籤)=$(this)

    // 如果用=>箭頭函式前面加上function, 他的this指的是click
    // (e) 代表全部事件都包在e裡面來, 如果要用箭頭函式寫要用以下寫法
        /* 
            $(".type").on('click',(e){
            console.log(e)
            $("#type").text($(e. target).text())
            })
        */

    getList(1)

    $(".type").on('click',function(){
        // console.log($(this).text())
        $("#type").text($(this).text())

        let type=$(this).data('type')
        getList(type)

})


function getList(type){
    /**
     * 1.有參數時,等同使用$.post
     * 2.無參數時,等同使用$.get
     */
    $("#postList").load("./api/get_list.php",{type})

    /* 以下這段式原始的寫法:
        $.get("./api/get_list.php",{type},(list)=>{
        $("#postList").html(list)
    }) 
    */
}

function getPost(id){
    $("#postList").load("./api/get_post.php",{id})
}

</script>