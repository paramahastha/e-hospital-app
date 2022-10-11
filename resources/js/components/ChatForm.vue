<template>
  <div class="input-group">    
    <input
      id="btn-input"
      type="text"
      name="message"
      class="form-control input-sm"
      placeholder="Type your message here..."
      v-model="newMessage"      
      @keyup.enter="addMessage"
    />    
    <span class="input-group-btn">      
      <button class="btn btn-primary btn-sm" id="btn-chat" @click="addMessage">
        Send
      </button>
    </span>
  </div>
</template>
<script>
export default {
  //Takes the "user" props from <chat-form> … :user="{{ Auth::user() }}"></chat-form> in the parent chat.blade.php.
  props: ["user", "consult", "messages"],
  data() {    
    return {
      newMessage: "",      
    };
  },
  methods: {
    addMessage() {
        //Pushes it to the messages array
        const message = {
          user: this.user,
          consult: this.consult,
          message: this.newMessage,
        }

        this.$emit("addmessage", message);

        this.newMessage = "";
        //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
        axios.post('/consult/messages/send', message).then(response => {
            console.log(response.data);
        });
    }
  },
};
</script>