$("body").on("change", "#attachment", function () {
    var output = document.getElementById("attachment-output");
    mimeType = event.target.files[0].type;
    if (mimeType.indexOf("image") != -1) {
        var reader = new FileReader();
        reader.onload = function (event) {
            $("#attachment-output").attr("src", event.target.result);
        };
        reader.readAsDataURL(event.target.files[0]);
    } else {
        output.src = noPreviewImagePath;
    }
    $(".attachment-name").text(event.target.files[0].name);
    $(".attachment-footer").show();
    $("#progress-bar").hide();
    $(".show-chat-attachment").show();
});

$(document).ready(function () {
    loadActiveChatMessage();
    $(".all-chats").click(function () {
        $(".all-chats").removeClass("active");
        $(this).addClass("active");
        loadActiveChatMessage();
    });

    $("#chat-message-form").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        formdata = new FormData(this);
        $.ajax({
            url: messengerRoutes.sendMessage,
            method: form.attr("method"),
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            success: function (response) {
                toggleLoader();
                if (response.success == true) {
                    resetChatForm();
                    $(".list-unstyled").append(response.html);
                    scrollToLastChat();
                }
            },
            error: function () {
                resetChatForm();
            },
        });
    });
});

$("body").on("submit", "#ajax-create-new-chat", function (e) {
    e.preventDefault();
    var form = $(this);
    formdata = new FormData(this);
    $.ajax({
        url: messengerRoutes.createNewChat,
        method: form.attr("method"),
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        success: function (response) {
            if (response.success == true) {
                resetChatForm();
                $(".chat-cont-left").html(response.html);
                loadActiveChatMessage();
                scrollToLastChat();
                $(".modal").modal("hide");
            }
        },
        error: function () {
            resetChatForm();
        },
    });
});

function loadActiveChatMessage() {
    let chat_id = $(".all-chats.active").data("id");
    $("[name='chat_id']").val(chat_id);
    $(".all-chats.active").find(".badge").remove();
    $.ajax({
        url: messengerRoutes.loadChatMessages,
        data: {
            chat_id: chat_id,
        },
        method: "POST",
        success: function (response) {
            if (response.success == true) {
                $(".chat-content").html(response.html);
                $(".chat-window .chat-cont-right").toggleClass("active");
                $(".chat-window .chat-cont-left").toggleClass("non-active");
            }
        },
    });
}

function scrollToLastChat() {
    $(".chat-scroll").animate({
        scrollTop: $("div.chat-scroll li:last").offset().top,
    });
}

function sendMessage() {
    $("#chat-message-form").submit();
    $("#progress-bar").show();
    $(".attachment-footer").hide();
}

function resetChatForm() {
    let form = $("#chat-message-form");
    form[0].reset();
    $(".show-chat-attachment").hide();
}

function fetchNewMessage() {
    chat_id = $("[name='chat_id']").val();
    $.ajax({
        url: messengerRoutes.fetchNewMessage,
        method: "POST",
        data: {
            chat_id: chat_id,
        },
        success: function (response) {
            if (response.success == true) {
                if (response.html != "") {
                    markPreviousChatAsRead();
                    $(".list-unstyled").append(response.html);
                    scrollToLastChat();
                }
                if (response.unreadMessages == 0) {
                    markPreviousChatAsRead();
                }
            }
        },
        error: function () {
            resetChatForm();
        },
    });
}

setInterval(function () {
    fetchNewMessage();
}, 5000);

function markPreviousChatAsRead() {
    $(".fa-check-double").addClass("is_message_read");
}
