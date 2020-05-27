<template>
  <b-card class="mb-3">
    <h1>Account</h1>
    <b-row>
      <b-col class="col-sm-6">
        <div v-if="branches.length != 0">
          <p>U beheert de tak(ken):</p>
          <ul>
            <li v-for="branch in branches" :key="branch.branchName">
              {{ branch.branchName }}
              <b-button variant="primary" size="sm" @click="editBranch(branch)">
                <span>Aanpassen</span>
              </b-button>
            </li>
          </ul>
          <p>Emails naar speltaknaam@scoutinghuissenzand.nl worden automatisch doorgestuurd naar uw eigen email.</p>
        </div>
        <div v-else>
          <p>U beheert geen speltakken</p>
        </div>
      </b-col>
      <b-col class="col-sm-6 ml-auto d-flex flex-column justify-content-end">
        <p class="ml-auto mb-auto">{{ user.email }}</p>
        <b-button variant="primary" class="mt-auto" @click="logOut">Uitloggen</b-button>
      </b-col>
    </b-row>
  </b-card>
</template>

<style>
.btn-sm {
  padding: 0.12rem 0.25rem;
  font-size: 0.7rem;
}
</style>


<script>
import axios from "@/plugins/axios.js";
import setCookie from "@/plugins/setCookie.js";
import { QuillDeltaToHtmlConverter } from "quill-delta-to-html";

export default {
  name: "logOut",
  props: ["user", "branches"],
  methods: {
    logOut() {
      axios
        .post("/user/logout")
        .then(response => {
          if (response.status === 201) {
            setCookie("loggedIn", "false");
            window.location.href = "/staff";
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
    },
    editBranch(branch) {
      branch.loading = true;
      axios
        .post("/branch/get",{branchName: branch.branchName})
        .then(response => {
          branch.loading = false;
          this.returned = true;
          if (response.status === 201) {
            if (response.data.branchDescription == null) {
              response.data.branchDescription = "";
            }
            var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(response.data.branchDescription),
            {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false}
          );
          response.data.branchDescription = converter.convert();
            this.$emit("editBranch", response.data);
          }
        })
        .catch(error => {
          branch.loading = false;
          this.$bvToast.toast(error + "", {
            title: "Error",
            autoHideDelay: 5000,
            appendToast: true
          });
        });
    }
  }
};
</script>

