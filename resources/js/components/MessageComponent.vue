<template>
        <div class="card">
            <div class="card-header">
                <b :class="{'text-danger': session.blocked}">
                    {{friend.name}} <span v-if="isTyping">is typing ...</span>
                    <span v-if="session.blocked">(Blocked)</span>
                </b>

                <a href="" @click.prevent="close" >
                    <i class="fa fa-times float-right" style="color : red"></i>
                </a>
                <div class="dropdown float-right mr-4">
                    <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="" @click.prevent="unblock" v-if="session.blocked && can">Unblock</a>
                        <a class="dropdown-item" href="" @click.prevent="block" v-if="!session.blocked">Block</a>
                        <a class="dropdown-item" href=""  @click.prevent="clear">Clear Chat</a>
                    </div>
                </div>
            </div>
            <div class="card-body chat-box"  v-chat-scroll>
                <p class="card-text" :class="{'text-right':chat.type == 0, 'text-success' :chat.read_at != null}" v-for="chat in chats"  :key="chat.id">
                        {{ chat.message}}
                </p>
            </div>
            <form class="card-footer" @submit.prevent="send">
                <div class="form-group">
                    <input type="text" placeholder="Type a message" class="form-control" :disabled="session.blocked" v-model="message">
                </div>
            </form>
        </div>

</template>
<script>
export default {
    props : ['friend'],
    data(){
        return {
            chats : [],
            message : null,
            isTyping : false,
        }
    },
    computed : {
        session(){
            return this.friend.session;
        },
        can(){
            return this.session.blocked_by == auth.id;
        }
    },
    watch: {
        message(value){
            if(value){
                Echo.private(`Chat.${this.friend.session.id}`)
                    .whisper('typing', {
                        name: auth.name
                    });
            }
        }

    },
    methods : {
        read(){
            axios.post(`/session/${this.friend.session.id}/read`);
        },
        send() {
            if(this.message){
                this.addMessage(this.message);
                axios.post(`/send/${this.friend.session.id}`, {
                    isi : this.message,
                    to_user : this.friend.id
                }).then(chat => {
                    this.chats[this.chats.length -1].id = chat.data
                });
                this.message = null;
            }
        },
        addMessage(message){
            this.chats.push({
                message : message,
                type : 0,
                read_at : null,
                created_at : 'Just now'
            });
        },
        getAllMessages(){
            axios.get(`/session/${this.friend.session.id}/chats`).then(res=>this.chats=res.data.data);
        },
        close(){
            this.$emit('close');
        },
        clear(){
            axios.post(`/session/${this.friend.session.id}/clear`).then(res=>this.chats = []);
        },
        block(){
            this.session.blocked = true
            axios.post(`/session/${this.friend.session.id}/block`).then(res => this.session.blocked_by = auth.id);
        },
        unblock(){
            this.session.blocked = false
            axios.post(`/session/${this.friend.session.id}/unblock`).then(res => this.session.blocked_by = null);
        }
    },
    created(){
        this.getAllMessages();
        this.read();
        Echo.private(`Chat.${this.friend.session.id}`).listen(
            "PrivateChatEvent",
            e => {
                this.friend.session.open ? this.read() : "";
                this.chats.push({
                    message : e.content,
                    type : 1,
                    created_at : e.created_at
                });
            }
        );
        Echo.private(`Chat.${this.friend.session.id}`).listen(
            "ReadEvent",
            e => {
                this.chats.forEach(chat => {
                    (chat.id == e.chat.id ? chat.read_at = e.chat.read_at : "");
                })
            }
        );
        Echo.private(`Chat.${this.friend.session.id}`).listen(
            "BlockEvent",
            e => {
                this.session.blocked = e.blocked;
            }
        );
        Echo.private(`Chat.${this.friend.session.id}`)
            .listenForWhisper('typing',
                e => {
                    this.isTyping = true
                    setTimeout(() => {
                        this.isTyping = false;
                    }, 2000);
                }
            );
    }
}
</script>
<style>
.chat-box {
    height : 300px;
}
.card-body {
    overflow-y : scroll
}
</style>
