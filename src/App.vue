<template>
  <div id="app">
    <VueNav v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs"></VueNav>
    <main :class="containsPage">
    <router-view v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs" />
    </main>
    <VueFooter v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs"></VueFooter>
  </div>
</template>


<script>
import axios from "@/plugins/axios.js";
import VueNav from "@/components/layout/nav.vue";
import VueFooter from "@/components/layout/footer.vue";
import isMobile from "@/plugins/isMobile";
import Vue from "@/main.js"

export default {
  name: "App",
  components: {
    VueNav,
    VueFooter
  },
  data() {
    return {
      branches: null,
      tabs: null,
      returned: false
    };
  },
  created() {
    try {
          var data = JSON.parse(document.getElementById("app").getAttribute("data-model"));
          this.branches = data.branches;
          this.tabs = data.tabs;
          this.returned = true;
          console.log("loaded from meta");
    } catch (e) {
      console.log("loading from api");
      axios
        .get("/info")
        .then(response => {
          this.returned = true;
          if (response.status === 200) {
            this.branches = response.data.branches;
            this.tabs = response.data.tabs;
          } else {
            this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
          }
        })
        .catch(error => {
          this.$bvToast.toast(error + "", Vue.toastObject("Error"));
        });
    }
  },
  computed:{
    containsPage(){
      return this.$route.path == "/index" || this.$route.path == "/" || isMobile() ? "page small-page" : "page";
    }
  }
};
</script>


<style>
main.page {
  padding-top: 122px !important;
  background-color: #f6f6f6;
}

@media (min-width: 576px) {
  main.page {
    padding-top: 144px !important;
  }
}

main.page.small-page {
  padding-top: 96px !important;
}

*{
  outline-color: rgba(0, 0, 0, 0) !important;
}

.btn-sm{
  border-radius: 1em;
}

html, body {
  min-height: 100vh;
  max-width: 100%;
  display: flex;
  flex-direction: column;
}

.card{
  border-radius: 1em;
}

main{
  flex-direction: column;
  flex: 1 1;
}

div#app{
  display: flex !important;
  flex-direction: column;
  flex: 1 1;
}

main section:first-of-type{
  flex: 1 1;
}

.flex-fill.dark{
  background-color: #f6f6f6;
}


.custom-file label{
  overflow-y: hidden;
}
</style>


