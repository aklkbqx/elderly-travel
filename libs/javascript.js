$(document).ready(() => {
    const alertMsg = $("#alertMsg");
    if (alertMsg) {
        const countMsg = $("#countMsg");
        const btnCloseMsg = $("#btnCloseMsg");
        alertMsg.removeClass("opacity-0");

        const closeMsg = ()=>{
            clearInterval(interval);
            alertMsg.addClass("opacity-0");
            setTimeout(()=>{
                alertMsg.remove();
            },400)
        }

        let count = 5;
        countMsg.text(count);
        const interval = setInterval(() => {
            if (count > 0) {
                count--;
                countMsg.text(count);
            } else {
                closeMsg()
            }
        }, 1000)

        if(btnCloseMsg){
            btnCloseMsg.on("click",()=>{
                closeMsg()
            })
        }
    }
    

    $("#addQuestion").on('click',()=>{
        const lastQeustion = $("#questions div:last-child");
        const newQuestion = lastQeustion.clone();
        const lastQuestionNumber = Number(lastQeustion.find("span").text());
        const newQuestionNumner = lastQuestionNumber + 1;

        newQuestion.find("button").removeClass("d-none");
        newQuestion.find('span').text(newQuestionNumner);
        newQuestion.find("input").val(null);

        $("#questions").append(newQuestion);
    });
    
    $(document).on("click", ".remove-question", function () {
        $(this).parent().remove();
        $('#questions div').each(function (index) {
            $(this).find('span').text(index + 1);
        });
    });

    $("additional-question").on("change",function(){
        $(this).parent()
    });
});

function openPassword(e) {
    const inputPassword = e.parent().find("input");
    if(inputPassword.attr("type") == "password"){
        e.attr("src","../images/web_images/icons/eye-slash.png");
        inputPassword.attr("type","text");
    }else{
        e.attr("src","../images/web_images/icons/eye.png");
        inputPassword.attr("type","password");
    }
}

function searchResult(e){
    const searchKeyword = e.val().toLowerCase();
    const searchItems = $("[data-search-item]");

    searchItems.each(function(){
        const item = $(this);
        const keyword = item.find('[data-search-keyword]').text().toLowerCase();
        if(keyword.includes(searchKeyword)){
            item.show();
        }else{
            item.hide();
        }
    });
}