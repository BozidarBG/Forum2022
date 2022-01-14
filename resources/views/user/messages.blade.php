@extends('layouts.users')
@section('styles')
    <style>
        body {
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            width: 5px;
            background: #f5f5f5;
        }

        ::-webkit-scrollbar-thumb {
            width: 1em;
            background-color: #ddd;
            outline: 1px solid slategrey;
            border-radius: 1rem;
        }

        .text-small {
            font-size: 0.9rem;
        }

        .messages-box,
        .chat-box {
            height: 510px;
            overflow-y: scroll;
        }


        .rounded-lg {
            border-radius: 0.5rem;
        }

        input::placeholder {
            font-size: 0.9rem;
            color: #999;
        }
        .active_chat{
            background-color: #6cb2eb;
        }
        .user-card:hover{
            background-color: #6cb2eb;
            cursor: pointer;
        }
        .search_card:hover{
            background-color: lightblue;
            cursor: pointer;
        }
        #messages_wrapper{
            min-height: 70vh;
        }
        .media{
            position:relative;
        }
        .unseen{
            position: absolute;
            top:-0.2rem;
            left:-0.3rem;
            height:1.7rem;
            background:red;
            color:white;
            padding:0.2rem;
            border-radius: 20%;
            font-size: 1rem;
            text-align: center;
        }
        .invisible{
            visibility: hidden;
        }
    </style>
@endsection
@section('title') messages @endsection

@section('content')

    <div class="col-md-12 px-5">
        <header class="text-center">
            <h1 class="display-5 ">Messenger</h1>
        </header>
        <div class="row rounded-lg overflow-hidden shadow">
            <!-- Users box-->
            <div class="col-4 px-0">
                <div class="bg-white">

                    <div class="bg-gray px-4 py-2 bg-light">
                        <button class="btn btn-primary mb-0 py-1" id="toggle_contacts_btn">Contacts</button>
                        <button class="btn btn-dark mb-0 py-1" id="toggle_search_btn">Search</button>
                    </div>

                    <div class="messages-box">
                        <div class="list-group rounded-0" id="contacts_wrapper">
                        <!-- contact divs are here -->
                        </div>
                        <div class="list-group rounded-0 d-none" id="search_wrapper">
                            <div class="card">
                                <div id="search_errors">

                                </div>
                                <form action="{{route('find.user')}}" id="find_user_form" method="post">
                                    @csrf
                                    <div class="card-body">
                                        <input type="text" class="form-control fc" placeholder="Enter username or min. 4 characters.." name="username" >
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-dark mb-0 py-1">Find user</button>
                                    </div>
                                </form>
                            </div>
                            <div id="search_results">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Chat Box-->
            <div class="col-8 px-0">
                <div class="px-4 py-5 chat-box bg-white" id="messages_wrapper">
                    <!-- messages for chosen contact -->
                </div>

                <!-- Typing area -->
                <form id="send-form" class="bg-light">

                <div class="input-group mb-3 px-3">
                    <input disabled id="input-message" type="text" class="form-control" placeholder="Type a message" >
                    <button disabled class="btn btn-outline-primary" type="button" id="button-addon2">Send</button>
                </div>

                   
                </form>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let user_id; //user that auth user is talking to
        let user_name; //user that auth user is talking to
        let user_avatar; //user that auth user is talking to
        let users_cards=document.getElementsByClassName('user-card');
        let auth_user_id="{{Auth::id()}}";
        let messages_wrapper=document.getElementById('messages_wrapper');
        let inputField=document.getElementById('input-message');
        let sendButton=document.getElementById('button-addon2');
        let contacts_wrapper=document.getElementById('contacts_wrapper');
        let contacts_ids=[];


        importContacts();

        tryToSendMessage();

        toggleContactsAndSearch();

        searchUsers();

        function toggleContactsAndSearch(){
            const toggleContactsBtn=document.getElementById('toggle_contacts_btn');
            const toggleSearchBtn=document.getElementById('toggle_search_btn');
            const contactsWrapper=document.getElementById('contacts_wrapper');
            const searchWrapper=document.getElementById('search_wrapper');

            toggleContactsBtn.addEventListener('click', (e)=>{
                if(contactsWrapper.classList.contains('d-none')){
                    contactsWrapper.classList.remove('d-none');
                    searchWrapper.classList.add('d-none');
                }
            });

            toggleSearchBtn.addEventListener('click', (e)=>{
                if(searchWrapper.classList.contains('d-none')){
                    contactsWrapper.classList.add('d-none');
                    searchWrapper.classList.remove('d-none');
                }
            });
        }

        function searchUsers(){
            const rules={
                'username': {minLength: 4, maxLength:50, required:true}
            }
            let form=document.getElementById('find_user_form');
            form.addEventListener('submit', (e)=>{
                e.preventDefault();
                document.getElementById('search_errors').innerHTML="";
                let search=new FormSubmition('find_user_form', rules, 'ajax', updateSearch);
            //console.log(search)
           
            if(search.hasErrors()){
                console.log(search.errorsObj)
                putErrorsInSingleDiv('search_errors', search.errorsObj.username, ['alert', 'alert-danger'])
            }else{
                search.sendPostViaAjax();
            }

            });

            
        }

        const updateSearch=(data)=>{
            //console.log(data)
            if(data.status==200 && data.data.users.length){
            // console.log(data.data.users)
                let search_results_wrapper=document.getElementById('search_results');
                search_results.innerHTML="";
                for(let i=0; i<data.data.users.length; i++){
                    //we have users so put them in search_results id
                    console.log(data.data.users[i])
                    let user=data.data.users[i];
                    let html= `
                    <a class="search_card list-group-item list-group-item-action text-dark rounded-0"  data-id="${user.id}">
                        <div class="media">
                            <img src="${user.avatar ? user.avatar : '/images/defaults/avatar.png'}" alt="user" width="50" class="rounded-circle position-relative">
                            
                            <div class="media-body ml-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h6 class="mb-0 name">${user.username}</h6>
                                </div>
                            </div>
                        </div>
                    </a>
            `;
                    search_results_wrapper.innerHTML+=html;
                }//end for
                //add click listener that will put user card into contact wrapper
                let results=document.getElementsByClassName('search_card');
                for(let i=0; i<results.length; i++){
                    results[i].addEventListener('click', (e)=>{
                        //videti da li je ovaj id u arr of ids za sve kontakte. ako jeste, onda samo taj kontakt prebaciti na vrh. ako nije, onda napraviti kontakt i staviti ga na vrh
                        let user_id=e.currentTarget.hasAttribute('data-id') ? e.currentTarget.getAttribute('data-id') : null;
                        //console.log(contacts_ids.includes(user_id))
                        if(contacts_ids.length && user_id && contacts_ids.includes(parseInt(user_id))){
                            //contact exists so we bring that contact card to the top of contact part and toggle d-none of contacts/search
                            //find card
                            let cards=contacts_wrapper.querySelectorAll('.user-card');

                            let card_index;
                            for(let i=0; i<cards.length; i++){
                                if(cards[i].getAttribute('data-id')== user_id){
                                    card_index=i;
                                    break;
                                }
                            }



                            let card_to_be_removed=cards.item(card_index);
                            card_to_be_removed.parentNode.removeChild(card_to_be_removed);
                            contacts_wrapper.prepend(card_to_be_removed);

                            const contactsWrapper=document.getElementById('contacts_wrapper');
                            const searchWrapper=document.getElementById('search_wrapper');
                            contactsWrapper.classList.remove('d-none');
                            searchWrapper.classList.add('d-none');
                        }else{
                            //contact doesn't exist so we make new user card, put it on the top of contact part and toggle d-none of contacts/search
                            const contactsWrapper=document.getElementById('contacts_wrapper');
                            const searchWrapper=document.getElementById('search_wrapper');

                            let user=data.data.users[i];
                            user.created_at=formatDateForContacts();
                            user.message="";
                            let new_contact_card=showContact(user);
                            let existing_contacts=contacts_wrapper.innerHTML;
                            let new_contacts=new_contact_card+existing_contacts;
                            contacts_wrapper.innerHTML=new_contacts;
                            selectChatForSingleUser();
                            contactsWrapper.classList.remove('d-none');
                            searchWrapper.classList.add('d-none');
 



                        }
                    });
                }

            }else{
                console.log('no users');
                search_results.innerHTML="<p class='alert alert-danger'>There are no users with such username! </p>";
            }
        }

        function importContacts() {
            let contacts_html="";
            axios.get('/get-contacts')
                .then((response)=>{
                //console.log(response.data.ids)
                    let contacts_arr=response.data.data;
                    contacts_ids=response.data.ids;
                    if(contacts_arr.length >0){
                        for(let i=0; i<contacts_arr.length; i++){
                            //  console.log(contacts_arr[i])
                            contacts_html += showContact(contacts_arr[i]);
                        }
                        contacts_wrapper.innerHTML +=contacts_html;
                        //when user clicks on conversation, we will receive all messages via ajax
                        selectChatForSingleUser();
                    }else{
                        contacts_wrapper.innerHTML="You don't have any chats started!";
                    }
                }).catch();
        }

        function showContact(user) {
            let unread_messages=user.unread_messages;
            //console.log('fun '+unread_messages)
            if(!unread_messages){
                unread_messages="0";
            }
            let insert=parseInt(unread_messages) ? `<div class="unseen ">${unread_messages}</div>` : `<div class="unseen invisible">${unread_messages}</div>`;

            let html= `
            <a class="list-group-item list-group-item-action text-dark rounded-0 user-card"  data-id="${user.id}">
                <div class="media">
                    <img src="${user.avatar ? user.avatar : '/images/defaults/avatar.png'}" alt="user" width="50" class="rounded-circle position-relative">
                    <span class="position-absolute left-50">${insert}</span>
                    <div class="media-body ml-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0 name">${user.username}</h6><small id="${user.id}_created" class="small font-weight-bold">${user.created_at}</small>
                        </div>
                        <p class="font-italic mb-0 text-small" id="${user.id}_message">${user.message}</p>
                    </div>
                </div>
            </a>
            `;

            return html;
        }

        //when new message is created, we update in contacts last message and date
        //we push this contact to the top
        function updateLastMessage(data){
            //console.log(created_at)
            let chat=parseInt(data.id)=== parseInt(auth_user_id) ? data.to : data.id;
            let messagePlaceholder=document.getElementById(chat+"_message");
            //console.log(data)
            let inner_text;
            let created_at;
            if(parseInt(data.id) === parseInt(auth_user_id)){
                inner_text="You: "+data.message;
                created_at=data.created_at;
            }else{
                inner_text=data.message;
                created_at=data.created_at_short;
            }
            messagePlaceholder.innerText=inner_text;


            let createdAtPlaceholder=document.getElementById(chat+"_created");
            createdAtPlaceholder.innerHTML=created_at;

            let thisElement=document.querySelector("a[data-id='"+chat+"']");

            contacts_wrapper.insertBefore(thisElement, contacts_wrapper.childNodes[0])
        }

        function tryToSendMessage() {
            //remove disabled on button when focused on input field
             inputField.addEventListener('focus', function(){
               if(sendButton.hasAttribute('disabled')){
                   sendButton.removeAttribute('disabled');
               }
               addSendMessageListeners();
            });
        }

        function addSendMessageListeners(){
            sendButton.addEventListener('click', function (e) {
               e.preventDefault();
               sendMessage();
            });
            inputField.addEventListener('keypress', function (e) {
                if(e.key==="Enter"){
                    e.preventDefault();
                    sendMessage();
                }
            });

        }

        function formatDateForContacts(){
            let created_at=new Date();

            let day=String(created_at.getDate()).length===1 ? "0"+String(created_at.getDate()) : String(created_at.getDate());
            let month=String(created_at.getMonth()+1).length ===1 ? "0"+String((created_at.getMonth()+1)) : String(created_at.getMonth()+1);
            let year=String(created_at.getFullYear()).slice(2,4);
            let formatedDate=day+"/"+month+"/"+year;
            return formatedDate;
        }

        function sendMessage(){
            //check if something is in the message
            let message=sanitize(inputField.value.trim());
            //check if something is in the message, if we have sender and if we have receiver
            if(message !=='' && auth_user_id && user_id){
                //send message to server via ajax but first, put that message in html. we don't need to wait for server response
                let created_at=new Date();
                let sent_msg={user_id, message, created_at};

                const new_msg=showAuthUserMessage(sent_msg);
                messages_wrapper.innerHTML +=new_msg;
                //we update last message with formated date 01/06/20
                

                let msg={};
                msg.message=message;
                msg.created_at=formatDateForContacts();
                msg.id=auth_user_id;
                msg.to=user_id;

                updateLastMessage(msg);

                scrollToBottomFunc();

                let csrf=document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                axios.post('/save-message', {
                    csrf,
                    message,
                    user_id
                }).then((response)=>{
                    //console.log(response)

                }).catch((error)=>{
                    //console.log(error)
                });
            }
            document.getElementById('send-form').reset();
        }

        //when user clicks on conversation, we will receive all messages via ajax
        function selectChatForSingleUser() {
            let users_cards=document.getElementsByClassName('user-card');
            for(let i=0; i<users_cards.length; i++){
                users_cards[i].addEventListener('click', function(){
                    //adds acitive class on active chat and removes active class from previous (if any) chat
                    addRemoveActiveChatClass(this);
                    //imports messages for selected contact
                    importChat(this);
                    //removes disabled prop from input field if it exists
                    removeDisabledFromInput();

                });
            }
        }

        //removes disabled prop from input field if it exists
        function removeDisabledFromInput(){
            if(inputField.hasAttribute('disabled')){
                inputField.removeAttribute('disabled');
            }
        }

        //adds acitive class on active chat and removes active class from previous (if any) chat
        function addRemoveActiveChatClass(t){
            let last_active=document.getElementsByClassName('active_chat');
                if(last_active[0]){
                    last_active[0].classList.remove('active_chat');
                }

            t.classList.add('active_chat');
        }

        //in the chat, this is html for message that auth user has sent
        function showAuthUserMessage(msg) {
            //console.log('auth ', msg)
            return `
            <div class="media w-100 ml-auto mb-3">
                <div class="media-body d-flex align-items-end justify-content-end flex-column">
                    <div class="w-75 bg-success rounded py-2 px-3 mb-2">
                        <p class="text-small mb-0 text-white text-end">${msg.message}</p>
                    </div>
                    <p class="small text-muted">${formatDate(msg.created_at)}</p>
                </div>
            </div>
            `;
        }

        //in the chat, this is html for message that other user has sent
        function showOtherPersonsMessage(msg) {
            console.log('other ', msg)
            return `
            <div class="media w-75 mb-3"><img src="${user_avatar}" alt="user" width="50" class="rounded-circle">
                <div class="media-body ml-3">
                     <div class="bg-warning rounded py-2 px-3 mb-2">
                           <p class="text-small mb-0 text-muted">${msg.message}</p>
                        </div>
                        <p class="small text-muted">${formatDate(msg.created_at)}</p>
                    </div>
                </div>
            `;
        }

        //imports messages for selected contact
        function importChat(t){
            user_id=t.getAttribute('data-id');
            user_name=t.getElementsByClassName('name')[0].innerText;
            user_avatar=t.getElementsByClassName('rounded-circle')[0].getAttribute('src');

            axios.get('/get-messages/'+user_id)
                .then((response)=>{

                    let messages_html='';

                    for(let i=0; i<response.data.data.length; i++){

                        if(response.data.data[i].to_user == user_id && response.data.data[i].from_user == auth_user_id){
                            messages_html += showAuthUserMessage(response.data.data[i]);
                        }else if(response.data.data[i].to_user == auth_user_id && response.data.data[i].from_user == user_id){
                            messages_html += showOtherPersonsMessage(response.data.data[i]);
                        }else{
                            //error on backend
                        }
                    }
                    messages_wrapper.innerHTML =messages_html;
                    scrollToBottomFunc();
                    //we need to remove unread messages mark from contact, because when we click on user and messages appear,
                    //we will assume that then all messages will be read
                    let unseen=t.getElementsByClassName('unseen')[0];
                    updateUnreadMessagesCount();
                    !unseen.classList.contains('invisible')  ? unseen.classList.add('invisible'):'';
                    unseen.innerHTML="0";
                });
        }

        function formatDate(date){
            let d=new Date(date)
            return d.getDate()+"."+(d.getMonth()+1)+"."+d.getFullYear()+" @ "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
        }

        var pusher = new Pusher('1c707f35b3558ecf0b80', {
            cluster: 'eu',
            forceTLS: true,
            enctypted: true
        });

        let channel = pusher.subscribe(auth_user_id);
        channel.bind('PrivateMessageSent', function (data) {
           // console.log(data)

            //is chat for this user already opened? if is, then add messages
            if(parseInt(user_id) === parseInt(data.data.id)){
                const new_msg=showOtherPersonsMessage(data.data);
                let mesg_wrapper=document.getElementById('messages_wrapper');
                mesg_wrapper.innerHTML +=new_msg;
            }

            updateLastMessage(data.data);

            if(!user_id || user_id != data.data.id){
                //update unread messages count
                //check how many are there and add 1 for data-id=some id
                let thisElement=document.querySelector("a[data-id='"+data.data.id+"']");
                let unseen=thisElement.getElementsByClassName('unseen')[0];
                let unseen_count=unseen.innerText;
                console.log('pu '+unseen_count)
                if(!unseen_count){
                    unseen_count=0;
                }
                console.log('pu2 '+unseen_count)
                unseen.innerHTML=parseInt(unseen_count)+1;
                unseen.classList.remove('invisible');
            }


            scrollToBottomFunc();

        });

        function sanitize(string) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#x27;',
                "/": '&#x2F;',
            };
            const reg = /[&<>"'/]/ig;
            return string.replace(reg, (match)=>(map[match]));
        }


        function scrollToBottomFunc() {
            $('#messages_wrapper')[0].scrollTop = $('#messages_wrapper')[0].scrollHeight
        }



    </script>
    @endsection
