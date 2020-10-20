  <template>
  <b-card class="mb-3">
    <b-form novalidate @submit.stop.prevent>
      <h2>Takken beheren</h2>
      <div class="form-group">
        <b-form-select :disabled="getWho === who" v-model="getWho">
          <template v-slot:first>
            <b-form-select-option disabled :value="null">-- Kies iemand als speltak beheerder --</b-form-select-option>
          </template>
          <b-form-select-option
            v-for="user in users"
            :key="user.email"
            :value="user.email"
          >{{ user.email }}</b-form-select-option>
          <b-form-select-option :value="user.email" :key="user.email">{{ user.email }}</b-form-select-option>
        </b-form-select>
      </div>
      <div class="form-group">
        <b-form-input
          autocomplete="nope"
          placeholder="Voer een bestaande tak in of maak een nieuwe aan."
          v-model="branch"
          list="branchList"
        ></b-form-input>
        <datalist id="branchList">
          <option
            v-for="branch in branches"
            :key="branch.branchName"
            :value="branch.branchName"
          >{{ (branch.branchAdmin ) ? `Toegewezen aan: ${branch.branchAdmin}` : "Niet toegewezen" }}</option>
        </datalist>
      </div>
      <div class="form-group d-flex justify-content-end mb-0 mt-3">
        <b-button class="mr-auto" variant="danger" v-on:click="attemptDelete">
          <span v-if="!deleting">Verwijderen</span>
          <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>
        </b-button>
        <b-button variant="primary" v-on:click="attemptToSubmit">
          <span v-if="!submitting">Opslaan</span>
          <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>
        </b-button>
      </div>
    </b-form>
  </b-card>
</template>



<script>
import axios from "@/plugins/axios.js";
import Vue from "@/main.js"


export default {
  name: "assignBranch",
  props: ["user", "who", "branches", "users"],
  data() {
    return {
      branchAdmin: null,
      branch: null,
      submitting: false,
      deleting: false
    };
  },
  computed: {
    getWho: {
      get() {
        for (var i = 0; i < this.users.length; i++) {
          if (this.users[i].email === this.who) {
            return this.who;
          }
        }
        return this.branchAdmin;
      },
      set(val) {
        this.branchAdmin = val;
      }
    }
  },
  methods: {
    attemptDelete() {
      if (this.branch ) {
        this.deleting = true;
        axios
          .delete("/branch/" + this.branch)
          .then(response => {
            this.deleting = false;
            if (response.status == 200) {
              for (var i = 0; i < this.branches.length; i++) {
                if (this.branches[i].branchName === this.branch) {
                  this.branches.splice(i, 1);
                }
              }
              this.$bvToast.toast("Tak verwijderd", Vue.toastObject("Succes"));
            } else {
              this.$bvToast.toast("Unknown", Vue.toastObject("Error"));
            }
          })
          .catch(error => {
            this.deleting = false;
            this.$bvToast.toast(error + "", Vue.toastObject("Error"));
          });
      } else {
        this.$bvToast.toast("Vul alle velden in.", Vue.toastObject("Error"));
      }
    },
    attemptToSubmit() {
      if (this.getWho  && this.branch !== "") {
        this.submitting = true;
        axios
          .post("/branch", {
            branchName: this.branch,
            branchAdmin: this.getWho
          })
          .then(response => {
            this.submitting = false;
            if (response.status == 201) {
              this.branches.push({
                branchName: this.branch,
                branchAdmin: this.getWho
              });

            } else if (response.status == 200) {
              for (var i = 0; i < this.branches.length; i++) {
                if (this.branches[i].branchName === this.branch) {
                  this.branches[i].branchAdmin = this.getWho;
                }
              }   
            }
            this.$bvToast.toast(response.data.message, Vue.toastObject("Succes"));
          })
          .catch(error => {
            this.submitting = false;
            if (error.response.status === 405) {
              this.$bvToast.toast("Unauthorized: OAuth Token possibilly expired. Neem contact op met de admin of stel handmatig de email accounts in", Vue.toastObject("Error"));
            } else if (error.response.status == 400) {
              this.$bvToast.toast("Tak niet toegevoegd", Vue.toastObject("Error"));
            } else {
              this.$bvToast.toast(error + "", Vue.toastObject("Error"));
            }
          });
      } else {
        this.$bvToast.toast("Vul alle velden in.", Vue.toastObject("Error"));
      }
    }
  }
};
</script>
  
 