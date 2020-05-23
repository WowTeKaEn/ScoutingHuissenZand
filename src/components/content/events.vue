<template>
  <b-card v-if="userBranches.length != 0" class="mb-3">
    <h1>Evenementen</h1>
    <b-form novalidate @submit.stop.prevent>
      <div class="form-group">
        <b-form-select v-model="getBranch">
          <template v-slot:first>
            <b-form-select-option disabled :value="null">-- Kies een speltak --</b-form-select-option>
          </template>
          <b-form-select-option
            v-for="branch in userBranches"
            :key="branch.branchName"
            :value="branch.branchName"
          >{{ branch.branchName }}</b-form-select-option>
        </b-form-select>
      </div>
      <div :key="branch" v-if="getBranch != null">
        <div class="d-flex flex-column"   v-if="returnedEvents">
          Evenementen van de {{ getBranch }}:
            <ul>
            <li v-for="eventf in branchEvents" :key="eventf.eventName">
              {{ eventf.eventName }}
              <b-button variant="primary" size="sm" @click="event = eventf">
                <span>Aanpassen</span>
              </b-button>
            </li>
          </ul>
        <b-button variant="primary" class="ml-auto" @click="event = {}">Nieuw evenement aanmaken</b-button>
        </div>
        <span v-else>
            <b-spinner variant="light" type="grow" label="Spinning"></b-spinner>
          </span>



      </div>
      <event :key="event.eventName" class="mt-3" v-if="event" v-bind:event="event"  v-bind:branch="getBranch" v-bind:branchEvents="branchEvents"></event>
    </b-form>
  </b-card>
</template>

<style>
.btn-sm {
  padding: 0.12rem 0.25rem;
  font-size: 0.7rem;
}

.asd__wrapper--full-screen {
  z-index: 100000 !important;
}
</style>


<script>
import event from "./event";
import axios from "@/plugins/axios.js";

export default {
  name: "events",
  props: ["user", "branches"],
  components: { event },
  data() {
    return {
      branch: null,
      returnedEvents: false,
      branchEvents: null,
      event: null,
    };
  },
  computed: {
    userBranches() {
      var branches = [];
      for (var i = 0; i < this.branches.length; i++) {
        if (this.branches[i].branchAdmin === this.user.email) {
          branches.push({
            branchName: this.branches[i].branchName
          });
        }
      }
      return branches;
    },
    getBranch:{
      get: function(){
        return this.branch;
      },
      set: function(val){
        this.returnedEvents = false;
        this.branchEvents = null;
        this.branch = val;
        this.event = null;
        axios
        .post("/event/get/branch", { branchName: this.getBranch })
        .then(response => {
          this.returnedEvents = true;
          this.branchEvents = response.data;
        })
        .catch(() => {
          this.returnedEvents = true;
        })
      }
    }
    
  },
  
};
</script>

