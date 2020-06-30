import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import NotFound from "../views/NotFound.vue";

Vue.use(VueRouter);

var titleAppend = " | Scouting Huissen Zand";

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
    meta: {title: () => { return "Index" + titleAppend }} 
  },
  {
    path: "/index",
    name: "Index",
    component: Home,
    meta: {title: () => { return "Index" + titleAppend }} 
  },
  {
    path: "/staf",
    name: "Staff",
    component: () => import("../views/staff/staffLogin.vue"),
    meta: {title: () => { return "Staf" + titleAppend }} 
  },
  {
    path: "/stafpaneel",
    name: "StaffPanel",
    component: () => import("../views/staff/staff.vue"),
    props: true,
    meta: {title: () => { return "Staf Paneel" + titleAppend }} 
  },
  {
    path: "/assignBranch/:who",
    name: "assignBranch",
    component: () => import("../views/staff/staff.vue"),
    props: true,
    meta: {title: () => { return "Staf Paneel" + titleAppend }} 
  },
  {
    path: "/login/:what/:who",
    name: "loginAssignBranch",
    component: () => import("../views/staff/staffLogin.vue"),
    props: true,
    meta: {title: () => { return "Staf Paneel" + titleAppend }} 
  },
  {
    path: "/validate/:who/:token",
    name: "validate",
    component: () => import("../views/staff/validate.vue"),
    props: true,
    meta: {title: () => { return "Valideren" + titleAppend }} 
  },
  {
    path: "/tab/:tab",
    component: () => import("../views/tab.vue"),
    props: true,
    meta: {title: route => { return route.params.tab + titleAppend }} 
  },
  { path: "/speltak/:branchName",
   component: () => import("../views/branch.vue"),
    props: true,
    meta: {title: route => { return route.params.branchName + titleAppend }} 
  },
  {
    path: "/speltak/:branchName/inschrijven",
    component: () => import("../views/enroll.vue"),
    props: true,
    meta: {title: route => {return "Inschrijven voor " + route.params.branchName + titleAppend }} 
  },
  { path: "/error/:error",
   component: NotFound,
   props: true,
   meta: {title: route => { return route.params.error + titleAppend }} 
  },
  { path: "*",
   component: NotFound,
   meta: {title: () => { return "Error" + titleAppend }} 
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title(to);
  next();
});

export default router;
