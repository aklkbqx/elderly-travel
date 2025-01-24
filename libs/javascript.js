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
            if (count <= 5) {
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
});