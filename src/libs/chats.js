$(document).ready(() => {
    const urlParams = new URLSearchParams(window.location.search);
    let sended = false;

    const messageBox = $("#message-box");
    const scrollToDownButton = $("#srollToDown");

    const toggleScrollButton = () => {
        if (messageBox.scrollTop() + messageBox.innerHeight() >= messageBox[0].scrollHeight) {
            scrollToDownButton.removeClass("d-flex").addClass("d-none");
        } else {
            scrollToDownButton.removeClass("d-none").addClass("d-flex");
        }
    }

    scrollToDownButton.on("click", () => {
        messageBox.scrollTop(messageBox.prop("scrollHeight"));
        scrollToDownButton.removeClass("d-flex").addClass("d-none");
    });

    const getMessage = () => {
        $.ajax({
            type: "GET",
            url: `../api/chats.php?getMessage&receiver_id=${urlParams.get("receiver_id")}`,
            success: function(response) {
                messageBox.html(response);
                if (sended) {
                    messageBox.scrollTop(messageBox.prop("scrollHeight"));
                    sended = false;
                    toggleScrollButton();
                }
                toggleScrollButton();
            }
        });
    }

    if (messageBox) {
        setInterval(() => getMessage(), 1000);
    }

    const formChat = $("#form-chat");
    formChat.on("submit", function(e) {
        e.preventDefault();
        const inputMessage = $(this).find("input[name=message]");

        // if (inputMessage.val().trim() == "") {
        //     return;
        // }

        const formData = new FormData($(this)[0]);

        $.ajax({
            type: "POST",
            url: `../api/chats.php?sendMessage&receiver_id=${urlParams.get("receiver_id")}`,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#chooseImage").val(null);$("#showImagePreview").removeClass("d-flex").addClass("d-none")
                inputMessage.val(null);
                sended = true;
            }
        });
    });

    messageBox.on("scroll", function() {
        toggleScrollButton();
    });
});
