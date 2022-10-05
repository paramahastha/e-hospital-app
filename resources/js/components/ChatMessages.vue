<template>
  <ul class="chat">
    <li class="left clearfix" v-for="message in messages" :key="message.id">
      <div class="clearfix">
        <div class="header">
          <strong>
            {{ message.user.name }}
          </strong>
        </div>
        <p>
          {{ message.message }}          
        </p>
      </div>
    </li>
  </ul>
</template>
<script>
export default {
  props: ["consult", "messages"],

  data() {
    return {
 
    }    
  },
  //Upon initialisation, run fetchMessages(). 
  created() {
      this.fetchMessages();

      window.Echo.private('chat').listen('MessageSent', (e) => {          
          this.$emit('addmessage', {
              message: e.message.message,
              user: e.user,
              consult: this.consult
          });
      });
  },
  methods: {
      fetchMessages() {            
          //GET request to the messages route in our Laravel server to fetch all the messages
          axios.post('/consult/messages/list', {
            id: this.consult.id
          }).then(response => {
              //Save the response in the messages array to display on the chat view                
              this.$emit('fetchmessage', response.data);
          });
      },
     
  }
};
</script>