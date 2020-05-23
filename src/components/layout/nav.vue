<template>
  <b-navbar toggleable="lg" class="navbar navbar-light fixed-top bg-white clean-navbar">
    <b-container>
      <b-navbar-brand href="/index" class="logo d-flex flex-row"><img class="mr-3" src="@/assets/img/navicon.png"><div class="m-auto" id='brand-name'>Scouting<br> Huissen Zand</div></b-navbar-brand>
      
      <b-navbar-toggle target="nav-collapse"> 
        <template v-slot:default="{ expanded }">
        <b-icon v-if="expanded" icon="chevron-bar-up"></b-icon>
        <b-icon v-else icon="chevron-bar-down"></b-icon>
      </template></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item href="/index">Home</b-nav-item>
          <b-nav-item
            v-for="tab in $props.tabs"
            :key="tab.tabName"
            :href="'/tab/' + tab.tabName"
          >{{ tab.tabName}}</b-nav-item>

          <b-nav-item-dropdown text="Speltakken" v-if="branches.length">
            <b-dropdown-item
              v-for="branch in $props.branches"
              :key="branch.branchName"
              :href="'/branch/' + branch.branchName"
            >{{ branch.branchName }}</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-container>
  </b-navbar>
</template>

<style>
.navbar-nav .dropdown-menu {
  position: absolute !important;
  float: none;
}
nav.navbar{
  background:transparent url("data:image/svg+xml;utf8,<svg width='100%' height='100%' preserveAspectRatio='none' version='1.1' xmlns='http://www.w3.org/2000/svg' class='wave'><defs></defs><path id='feel-the-wave-two' d=''/></svg>") no-repeat;
  /* background-size: 100vw 100%; */
}
.navbar-toggler{
width: 60px;
height: 60px;
border: none !important;
}
@media only screen and (max-width: 600px) {
  #brand-name{
    display: none;
  }
}
.navbar img{
  border: 0 !important;
}

</style>

<script>
export default {
  name: "vueNav",
  props: ["tabs", "branches"]
};
</script>