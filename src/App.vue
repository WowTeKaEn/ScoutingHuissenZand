<template>
  <div id="app">
    <VueNav v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs"></VueNav>
    <router-view v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs" />
    <VueFooter v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs" ></VueFooter>
  </div>
</template>


<script>
import axios from "@/plugins/axios.js";
import VueNav from '@/components/layout/nav.vue'
import VueFooter from '@/components/layout/footer.vue'

export default {
  name: 'App',
  components: {
    VueNav,
    VueFooter
  },
  data(){
    return{
      branches: null,
      tabs: null,
      returned: false,
    }
  },
  created(){
    axios
          .get("/info") 
          .then(response => {
            this.returned = true;
            if (response.status === 200) {
               this.branches = response.data.branches;
               this.tabs = response.data.tabs;
            }else{
                this.$bvToast.toast("Unknown", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
              });
            }
          }).catch(error =>{
             this.$bvToast.toast(error + "", {
                title: "Error",
                autoHideDelay: 5000,
                appendToast: true
          });
          });
  } 
}
</script>


<style>
.navbar{
  padding: 0;
}

main.page{
  padding-top: 122px !important;
}

@media (min-width: 576px){
main.page{
  padding-top: 144px !important;
}
}

</style>


