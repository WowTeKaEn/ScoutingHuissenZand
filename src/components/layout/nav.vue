<template>
  <b-navbar
    id="navbar"
    toggleable="lg"
    class="flex-fill navbar-light fixed-top bg-white clean-navbar"
  >
    <div class="w-25 d-flex">
      <b-navbar-brand href="/index" class="logo d-flex flex-row ml-auto">
        <img class="mr-3" src="@/assets/img/navicon.png" />
        <div class="my-auto" id="brand-name">
          Scouting
          <br />Huissen Zand
        </div>
      </b-navbar-brand>
    </div>
    <b-navbar-toggle target="nav-collapse">
      <template v-slot:default="{ expanded }">
        <b-icon v-if="expanded" icon="chevron-bar-up"></b-icon>
        <b-icon v-else icon="chevron-bar-down"></b-icon>
      </template>
    </b-navbar-toggle>
    <b-collapse id="nav-collapse" is-nav class="w-75 ml-auto">
      <b-navbar-nav class="ml-auto">
        <b-nav-item
          v-for="tab in $props.tabs"
          :key="tab.tabName"
          :href="'/tab/' + tab.tabName"
        >{{ tab.tabName}}</b-nav-item>
        <div @mouseover="onOver" @mouseleave="onLeave">
          <b-nav-item-dropdown ref="dropdown" text="Speltakken" v-if="branches.length">
            <b-dropdown-item
              v-for="branch in $props.branches"
              :key="branch.branchName"
              :href="'/speltak/' + branch.branchName"
            >{{ branch.branchName }}</b-dropdown-item>
          </b-nav-item-dropdown>
        </div>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<style>
.navbar-nav .dropdown-menu {
  position: absolute !important;
  float: none;
}
.navbar-toggler {
  width: 60px;
  height: 60px;
  border: none !important;
}

.navbar.transition {
  transition: 0.35s;
}

.navbar.small {
  padding: 0 1rem 0 1rem !important;
  
}

.navbar .navbar-brand.transition{
  transition: 0.35s;
}

.navbar.small .navbar-brand{
  padding: 0 !important;
}
@media only screen and (max-width: 600px) {
  #brand-name {
    display: none;
  }
}
.navbar img {
  border: 0 !important;
}
.dropdown-menu {
  top: 90% !important;
}
</style>

<script>
import isMobile from "@/plugins/isMobile";

export default {
  name: "vueNav",
  props: ["tabs", "branches"],
  methods: {
    onOver() {
      if (!isMobile()) {
        this.$refs.dropdown.visible = true;
      }
    },
    onLeave() {
      if (!isMobile()) {
        this.$refs.dropdown.visible = false;
      }
    }
  },
  mounted() {
    if(!isMobile()){
      window.onscroll = function() {
      scrollFunction();
      };
      document.getElementById("navbar").classList.add("transition");
      document.querySelector('.navbar .navbar-brand').classList.add("transition");
    }else{
      document.getElementById("navbar").classList.add("small");
    }
    function scrollFunction() {
      if (
        document.body.scrollTop > 200 ||
        document.documentElement.scrollTop > 200
      ) {
        document.getElementById("navbar").classList.add("small");
      } else {
        document.getElementById("navbar").classList.remove("small");
      }
    }
  }
};
</script>