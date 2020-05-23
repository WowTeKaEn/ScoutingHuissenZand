<template>
  <main class="page">
    <section v-if="returned && who == null" class="clean-block p-0 py-5 dark">
      <b-container>
      <!-- <b-row class="w-100 h-100 m-0">
        <b-col  class="py-5 col-lg-2  bg-white mr-auto">
          <nav class="nav flex-column">
            <a class="p-1 nav-link active" href="#">Active</a>
            <a class="p-1 nav-link" href="#">Link</a>
            <a class="p-1 nav-link" href="#">Link</a>
            <a class="p-1 nav-link disabled" href="#">Disabled</a>
          </nav>
        </b-col>
        <b-col class="py-5" style="min-width:350px;"> -->
          <tabInsert v-if="user.admin == 1" ref="tabInsert" v-bind:tabs="tabs"></tabInsert>
          <tabDelete @editTab="editTab" v-if="user.admin == 1" v-bind:tabs="tabs"></tabDelete>
          <assignBranch v-if="user.admin == 1" v-bind:user="user" v-bind:branches="branches"></assignBranch>
          <account @editBranch="editBranch" v-bind:user="user" v-bind:branches="branches"></account>
          <editBranch v-if="currentBranch != null" v-bind:branch="currentBranch"></editBranch>
          <events v-bind:user="user" v-bind:branches="branches"></events>
      </b-container>
        <!-- </b-col> -->
        <!-- <div class="col-lg-2 p-0" style="height:0px; width:0;"></div> -->
      <!-- </b-row> -->
    </section>
    <section v-else-if="returned && user.admin == 1 && who != null" class="clean-block p-0 py-5 dark">
      <b-container>
        <assignBranch v-bind:user="user" v-bind:branches="branches" v-bind:who="who"></assignBranch>
      </b-container>
    </section>
    <section v-else-if="!returned" class="clean-block p-0 py-5 dark">
      <b-container class="d-flex justify-content-center">
        <span class="my-auto">
          <b-spinner variant="primary" label="Spinning"></b-spinner>
        </span>
      </b-container>
    </section>
  </main>
</template>



<script>
import tabInsert from "@/components/content/tabInsert.vue";
import tabDelete from "@/components/content/tabDelete.vue";
import assignBranch from "@/components/content/assignBranch";
import editBranch from "@/components/content/editBranch";
import account from "@/components/account/account.vue";
import axios from "@/plugins/axios.js";
import events from "@/components/content/events.vue"
import setCookie from "@/plugins/setCookie.js";

export default {
  name: "staff",
  components: { editBranch, assignBranch, tabInsert, tabDelete, account, events },
  props: ["branches", "tabs", "who"],
  data() {
    return {
      user: null,
      returned: false,
      currentBranch: null
    };
  },
  methods: {
    editTab(tab) {
      this.$refs.tabInsert.setEditTab(tab.tabName, tab.tabDescription);
    },
    editBranch(branch) {
      this.currentBranch = branch;
    }
  },
  created() {
    axios
      .get("/user/get")
      .then(response => {
        if (response.status === 200) {
          this.user = response.data;
          setCookie("loggedIn", "true");
          if (this.who != null && this.user.admin != 1) {
            window.location.href = "/error/401";
          }
          this.returned = true;
        } else {
          this.$bvToast.toast("Unknown", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        }
      })
      .catch(error => {
        this.returned = true;
        this.$bvToast.toast(error + "", {
          title: "Error",
          autoHideDelay: 5000,
          appendToast: true
        });
        setCookie("loggedIn", "false");
        window.location.href = "/staff";
      });
  }
};
</script>