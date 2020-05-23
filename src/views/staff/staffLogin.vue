<template>
  <main class="page">
    <section class="clean-block p-0 py-5 dark">
      <b-container v-if="visible">
        <loginForm v-bind:what="what" v-bind:who="who" class="card-form"></loginForm>
        <registerForm v-if="what == null" class="card-form"></registerForm>
      </b-container>
    </section>
  </main>
</template>

<style>
.card-form {
  max-width: 400px;
  margin: auto;
}
</style>

<script>
import loginForm from "@/components/account/loginForm";
import registerForm from "@/components/account/registerForm";
import getCookie from "@/plugins/getCookie.js";
import router from "@/router/index"

export default {
  name: 'staffLogin',
  props: ["what","who"],
  components:{loginForm, registerForm},
  data(){
    return{
      visible: false,
    }
  },
  created(){
    if(getCookie("loggedIn") == "true"){
      if(this.what == null && this.who == null){
        router.push("/StaffPanel");
      }else{
        router.push("/" + this.what + "/" + this.who);
      }
    }else{
      this.visible = true;
    }
  }
}
</script>