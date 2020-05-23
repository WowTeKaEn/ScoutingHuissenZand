import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import NotFound from "../views/NotFound.vue";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/index',
    name: 'Index',
    component: Home
  },
  {
    path: '/pricing',
    name: 'Pricing',
    component: () => import('../views/pricing.vue')
  },
  {
    path: '/staff',
    name: 'Staff',
    component: () => import('../views/staff/staffLogin.vue')
  },
  {
    path: '/staffpanel',
    name: 'StaffPanel',
    component: () => import( '../views/staff/staff.vue'),
    props: true
  },
  {
    path: '/assignBranch/:who',
    name: 'assignBranch',
    component: () => import( '../views/staff/staff.vue'),
    props: true
  },
  {
    path: '/login/:what/:who',
    name: 'loginAssignBranch',
    component: () => import( '../views/staff/staffLogin.vue'),
    props: true
  },
  {
    path: '/validate/:who/:token',
    name: 'validate',
    component: () => import( '../views/staff/validate.vue'),
    props: true
  },
  { path: '/tab/:tab', component: () => import('../views/tab.vue'), props: true },
  { path: '/branch/:branchName', component: () => import('../views/branch.vue'), props: true },
  { path: '/error/:error', component: NotFound, props: true },
  { path: '*', component: NotFound }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
