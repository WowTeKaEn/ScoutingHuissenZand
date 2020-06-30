<template>
  <div id="app">
    <VueNav v-if="returned" v-bind:branches="branches" v-bind:tabs="tabs"></VueNav>
    <main class="page">
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
            this.$bvToast.toast("Unknown", {
              title: "Error",
              autoHideDelay: 5000,
              appendToast: true
            });
          }
        })
        .catch(error => {
          this.$bvToast.toast(error + "", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        });
    }
  },
  mounted(){
    if(isMobile()){
      document.getElementsByClassName("page")[0].classList.add("small");
    }
  }
};
</script>


<style>
.navbar {
  padding: 0;
}
main.page {
  padding-top: 122px !important;
}

@media (min-width: 576px) {
  main.page {
    padding-top: 144px !important;
  }
}

main.page.small {
  padding-top: 96px !important;
}

*{
  outline-color: rgba(0, 0, 0, 0) !important;
}


section{
  position: relative;
}
</style>


