<template>
    <section v-if="returned && who == null" class="clean-block p-0 py-5 dark">
      <b-container>
        <b-tabs content-class="mt-3">
          <b-tab title="Account" active>
            <account @editBranch="editBranch" v-bind:user="user" v-bind:branches="user.branches"></account>
            <editBranch v-if="currentBranch != null" v-bind:branch="currentBranch"></editBranch>
          </b-tab>
          <b-tab title="Evenementen" v-if="user.branches.length > 0">
            <events v-bind:user="user" v-bind:branches="user.branches"></events>
          </b-tab>
          <b-tab title="Foto's" v-if="user.branches.length > 0">
            <imageEditor v-bind:user="user" v-bind:branches="user.branches"></imageEditor>
          </b-tab>
          <b-tab title="Tabbladen" v-if="user.admin == 1">
            <tabInsert ref="tabInsert" v-bind:tabs="tabs"></tabInsert>
            <tabDelete @editTab="editTab" v-bind:tabs="tabs"></tabDelete>
          </b-tab>
          <b-tab title="Speltakken" v-if="user.admin == 1">
            <assignBranch v-bind:user="user" v-bind:branches="branches"></assignBranch>
          </b-tab>
        </b-tabs>
      </b-container>
    </section>
    <section
      v-else-if="returned && user.admin == 1 && who != null"
      class="clean-block p-0 py-5 dark"
    >
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
</template>



<script>
import tabInsert from "@/components/content/tabs/tabInsert.vue";
import tabDelete from "@/components/content/tabs/tabDelete.vue";
import assignBranch from "@/components/content/branches/assignBranch";
import editBranch from "@/components/content/branches/editBranch";
import account from "@/components/account/account.vue";
import axios from "@/plugins/axios.js";
import events from "@/components/content/events/events.vue";
import imageEditor from "@/components/content/branches/imageEditor.vue";
import setCookie from "@/plugins/setCookie.js";

export default {
  name: "staff",
  components: {
    editBranch,
    assignBranch,
    tabInsert,
    tabDelete,
    account,
    events,
    imageEditor
  },
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
        window.location.href = "/staf";
      });
  }
};
</script>