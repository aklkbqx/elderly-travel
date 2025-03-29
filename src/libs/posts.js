function likePost (element,post_id){
    $.ajax({
        url: `../api/posts?sendLike&post_id=${post_id}`,
        success: (response) =>{
            if(response){
                element.removeClass("btn-outline-light text-dark").addClass("btn-teal")
                element.find("img").attr("src","../images/web_images/icons/heart-red.png");
                element.find("div").text("ถูกใจแล้ว");
            }else{
                element.addClass("btn-outline-light text-dark").removeClass("btn-teal")
                element.find("img").attr("src","../images/web_images/icons/heart-black.png");
                element.find("div").text("ถูกใจ");
            }
        }
    })

}