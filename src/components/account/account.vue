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
import VueMixin from "@/main.js"

export default {
  name: "logOut",
  props: ["user", "branches"],
  methods: {
    logOut() {
      axios
        .delete("/user")
        .then(response => {
          VueMixin.throwResponse(response, null, () => {
           setCookie("loggedIn", "false");
            window.location.href = "/staf";
          });
        })
        .catch(error => {
          VueMixin.throwError(error);
        });
    },
    editBranch(branch) {
      branch.loading = true;
      axios
        .get("/branch/" + branch.branchName)
        .then(response => {
          VueMixin.throwResponse(response, null, () => {
           if (response.data.branchDescription == null) {
              response.data.branchDescription = "";
            }
            var converter = new QuillDeltaToHtmlConverter(
            JSON.parse(response.data.branchDescription),
            {multiLineParagraph: false, multiLineBlockquote: false, multiLineHeader: false, multiLineCodeblock: false}
          );
          response.data.branchDescription = converter.convert();
            this.$emit("editBranch", response.data);
          });
          branch.loading = false;
          this.returned = true;
        })
        .catch(error => {
          VueMixin.throwError(error,()=>{
            branch.loading = false;
          });
        });
    }
  }
};
</script>

