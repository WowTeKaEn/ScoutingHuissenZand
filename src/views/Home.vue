<template>
  <main class="page landing-page">
    <section class="clean-block clean-hero">
      <div class="text">
        <h2>Welkom bij Scouting Huissen Zand</h2>
        <p>Welkom bij de vernieuwde website van Scouting Huissen Zand.</p>
        <button
          v-if="tabs.forEach(tab => { if(tab.tabName == 'Test'){ return true;}})"
          class="btn btn-outline-light btn-lg"
          type="button"
        >Learn More</button>
      </div>
    </section>
    <section class="clean-block clean-info dark">
      <b-container class="mt-5">
        <div class="block-heading">
          <h2 class="text-info">Speltakken</h2>
          <p>Dit zijn de verschillende speltakken van Scouting Huissen Zand</p>
        </div>
        <b-row>
          <b-col v-for="branch in branches" :key="branch.branchName" class="d-flex flex-column">
            <teammember v-bind:branch="branch"></teammember>
          </b-col>
        </b-row>
      </b-container>
    </section>
  </main>
</template>

<script>

import teammember from "@/components/teammember.vue"

export default {
  name: "Home",
  props: ["tabs", "branches"],
  components: { teammember },
  methods: {
    tabsContains(name) {
      this.tabs.forEach(tab => {
        if (tab.tabName == name) {
          return true;
        }
        console.log(tab.tabName);
      });
      return false;
    },
    
  },
};
</script>

<style>
.clean-hero {
  color: #28a745;
}




</style>