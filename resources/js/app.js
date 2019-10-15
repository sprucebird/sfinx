
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import swal from 'sweetalert';


const feather = require('feather-icons')
feather.replace();




/*
Vue.js components defined here
*/
window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueCookies from  'vue-cookies'
Vue.use(VueRouter);
Vue.use(VueCookies);

//import DateTimePicker & it's settings
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
Vue.use(datePicker);


import axios from 'axios';

/**
 * Uncomment below when compiling to production
 */
// Vue.config.devtools = false;
// Vue.config.debug = false;
// Vue.config.silent = true;

import VueApexCharts from 'vue-apexcharts'

Vue.component('apexchart', VueApexCharts)

// 1. Define route components.
// These can be imported from other files
import navigation from './components/navigation.vue';

import signups from './components/signup-requests.vue';

import home from './components/home.vue';
import groups from './components/groups.vue';
import groupsUpdate from './components/groups-update.vue';
import members from './components/members.vue';
import entries from './components/entries.vue';
import newEntrie from './components/newCheck.vue';
import payments from './components/payments.vue';
import competition from './components/competition-show.vue';

import groupsCreate from './components/groups-create.vue';
import membersAdd from './components/members-add.vue';

import memberEdit from './components/members-edit.vue';
import userAdd from './components/user-add.vue';
import users from './components/users.vue';
import search from './components/searchDialog.vue';
// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.



// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
  { path: '/', component: home },
  { path: '/signups', component: signups },
  { path: '/groups', component: groups },
  { path: '/members', component: members },
  { path: '/groups', component: groups },
  { path: '/groups/create', component: groupsCreate },
  { path: '/groups/update/:id', component: groupsUpdate, name: 'group' },
  { path: '/payments', component: payments },
  { path: '/entries', component: entries },
  { path: '/treniruote', component: newEntrie },
  { path: '/competition', component: competition },
  { path: '/members/add', component: membersAdd, name: 'add' },
  { path: '/members/edit/:id', component: memberEdit, name: 'edit' },
  { path: '/signups/confirm/:id', component: membersAdd, name: 'sign_confirm'},
  { path: '/settings/users', component: users},
  { path: '/settings/users/create', component: userAdd},
  { path: '/search', component: search},
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  mode: 'history',
  linkExactActiveClass: 'is-active',
  routes // short for `routes: routes`
})

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.

// register modal component
Vue.component('search-modal', {
  data: function () {
    return {
      q: null,
      search_results: [],
      search_status: null,
      authenticatedUser: null,
    }
  },
  watch: {
    q(after, before) {
      this.makeSearch();
    }
  },
  methods: {
    makeSearch: function (){
      if(this.q == ''){
        this.search_results = null;
      }else{
        axios.post('/api/search', {
          searchQ: this.q,
        }).then(response => {
          this.search_results = response.data;
          if(this.search_results.status == "error"){
            this.search_status = false;
          }else{
            this.search_status = true;
          }
        });
      }

    }
  },
  template: '#modal-search',
});

const app = new Vue({
  data: {
    showSearchModal: false,
    showConfirmMemberModal: false,
    selectedMemberID: 0,
    dark: false,

  },
  methods: {
    makeSearch: function (){
      if(this.q == ''){
        this.search_results = null;
      }else{
        axios.post('/api/search', {
          searchQ: this.q,
        }).then(response => {
          this.search_results = response.data;
          if(this.search_results.status == "error"){
            this.search_status = false;
          }else{
            this.search_status = true;
          }
        });
      }

    },
    cook() {
        if($cookies.isKey("dark")) this.dark = ($cookies.get("dark") == "true" ? true : false);
        else $cookies.set("dark", false);

        if(this.dark) document.body.classList.add("change");
    },
    cdark() {
      $cookies.set("dark", this.dark);
      if(this.dark) document.body.classList.add("change");
      else document.body.classList.remove("change");
    },
    scanRFID() {
      swal({
        title: 'Nuskenuokite RFID įrenginį',
        content: {
          element: "input",
          placeholder: "RFID kortelės duomenys"
        },
        button: {
          cancel: true,
          confirm: true,
        }
      }).then(value => {
        if(value != null) {
          axios.post("/rfid/scan", {
            RFID: value,
            scanOnly: true
          }).then(response => {
            if(response.data.status == "OK") {
              this.$router.push({name: 'edit', params: {id: response.data.owner.id} });
            }
            else {
              swal("Atsiprašome", "Sistemoje įvyko klaida. Norėdami užtikrinti jos pašalinimą, prašome apie ją pranešti techninio aptarnavimo personalui. Dėkojame už Jūsų supratingumą", "error");
            }
          });
        }
      });
    },
    newPayment(id, member) {
      swal({
        title: "Naujas mokėjimas",
        text: "Nustatytas nario mokestis pasirinktam nariui: " + member.fee + " euru",
        input: 'number',
        inputValue: member.fee,
        icon: "info",
        buttons: true,
        closeModal: true,
        dangerMode: false,
      }).then(value => {
        if(value)
        axios.post('/payments/new', {
          'member': id,
          'price': member.fee,
        }).then(response => {
          if(response.data.status == 'OK') {
            swal({title: "Mokejimas padarytas sekmingai", icon: "success"});
            setTimeout(()=> {swal.close()}, 1000);
          }
          else {
            swal("Atliekant procedura ivyko serverio klaida. Atsiprasome uz laikinus nesklandumus!","" ,"error");
          }
        });
      });
    }
  },
  mounted() {
    this.cook();
  },
  router
}).$mount('#app')

// Now the app has started!
/*
-----------------------------
*/

// var Chart = require('chart.js');

// var ctx = document.getElementById('balanceChart');
// var ctx2 = document.getElementById('paymentsChart');
// var ctx3 = document.getElementById('economyChart');
// $(document).ready(function(){
// 	$.get("/api/stats/balance/studio", function(data, status){
// 	     var myChart = new Chart(ctx, {
// 		    type: 'bar',
// 		    data: {
// 		        labels: data.months,
// 		        datasets: [{
// 		            label: 'Balansas eurais',
// 		            data: data.balances,
// 		            backgroundColor: '#6E86F0',
// 		            borderWidth: 1
// 		        }]
// 		    },
// 		    options: {
// 		        scales: {
// 		            yAxes: [{
// 		                ticks: {
// 		                    beginAtZero: true
// 		                }
// 		            }]
// 		        }
// 		    }
// 		});
//     });

//     $.get("/api/stats/payments/studio", function(data, status){
// 	     var myChart2 = new Chart(ctx2, {
// 		    type: 'bar',
// 		    data: {
// 		        labels: data.months,
// 		        datasets: [{
// 		            label: 'Balansas eurais',
// 		            data: data.payments,
// 		            backgroundColor: '#6E86F0',
// 		            borderWidth: 1
// 		        }]
// 		    },
// 		    options: {
// 		        scales: {
// 		            yAxes: [{
// 		                ticks: {
// 		                    beginAtZero: true
// 		                }
// 		            }]
// 		        }
// 		    }
// 		});
//     });
// });

$(document).ready(function(){
  $("#sideNavigation").hover(function(){
    $('#sideNavigation').css('width', '220px');
  });
});
