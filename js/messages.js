makeActiveLink("profile");
function displayChats(chats,container,chatContainer) {
    empty(container);
    var chatLis = [];
    var chatAs = [];
    for(var i = 0; i < chats.length; ++i) {
        chatLis[i] = document.createElement("li");
        chatAs[i] = document.createElement("a");
        chatAs[i].textContent = chats[i].username;
        chatAs[i].href = "#";
        chatLis[i].appendChild(chatAs[i]);
        container.appendChild(chatLis[i]);
        showChatAction(chatAs[i],chats[i],chatContainer);
    }
}

function displayMessages(result,chatContainer) {
    empty(chatContainer);
    var resultObj = JSON.parse(result);
    var messages = resultObj.data;
    var messagesLis = [];
    var messagesPics = [];
    for(var i = 0; i < messages.length; ++i) {
        messagesLis[i] = document.createElement("li");
        messagesLis[i].textContent = recape(messages[i].messageBody);
        if(messages[i].srcId == userId)
            messagesLis[i].className = "to";
        else
            messagesLis[i].className = "from";
        chatContainer.appendChild(messagesLis[i]);
        if(messages[i].path) {
            messagesPics[i] = document.createElement("img");
            messagesPics[i].alt = "attachment";
            messagesPics[i].src = messages[i].path;
            messagesPics[i].className = "message-pic";
            messagesLis[i].appendChild(document.createElement("hr"));
            messagesLis[i].appendChild(messagesPics[i]);
        }

    }
}

function showChatAction(anchor,chat,chatContainer) {
    anchor.onclick = function(){
        console.log(chat);
        document.querySelector(".chat-name").textContent = chat.username;
        document.getElementById("chat-form").dest.value = chat.userId;
        get("./php/userRouter.php?route=getMessages&buddy="+chat.userId,function(result) {
            displayMessages(result,chatContainer);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    }
}

setFileTrigger(document.getElementById("pic-file"),document.getElementById("chat-form").attachment);
checkForm(document.getElementById("chat-form"));

setAjax(document.getElementById("chat-form"),function(result) {
    var buddy = document.getElementById("chat-form").dest.value;
    document.getElementById("chat-form").reset();
    get("./php/userRouter.php?route=getMessages&buddy="+buddy,function(result) {
        displayMessages(result,document.getElementById("message-list"));
        document.getElementById("message-list").scrollTop = document.getElementById("message-list").scrollHeight;
    });
})

function chatsLoaded(result,container) {
    var resultObj = JSON.parse(result);
    var chats = resultObj.data;
    displayChats(chats,container,document.getElementById("message-list"));
}

get("./php/userRouter.php?route=getChats",function(result) {
    chatsLoaded(result,document.getElementById("chat-list"));
})

document.getElementById("chat-toggle").onclick = function() {
    document.getElementById("chat-list").style.display = "block";
    document.getElementById("user-list").style.display = "none";
    document.getElementById("chat-toggle").style.fontFamily = "rr";
    document.getElementById("user-toggle").style.fontFamily = "rt";
    get("./php/userRouter.php?route=getChats",function(result) {
        chatsLoaded(result,document.getElementById("chat-list"));
    })
}

document.getElementById("user-toggle").onclick = function() {
    document.getElementById("chat-list").style.display = "none";
    document.getElementById("user-list").style.display = "block";
    document.getElementById("chat-toggle").style.fontFamily = "rt";
    document.getElementById("user-toggle").style.fontFamily = "rr";
    get("./php/userRouter.php?route=getFollowed&id="+userId,function(result) {
        chatsLoaded(result,document.getElementById("user-list"));
    })
}