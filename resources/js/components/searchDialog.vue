<template>
  <div>
    <div class="container">
       <div class="sectionHeader mb-4">
          <h1>Paieska</h1>
        </div>
        <div class="sectionHeader mb-4">
           <input type="text" v-model="q" ref="search" class="form-control form-control-search" v-on:keyup="makeSearch();"></input>
         </div>
        <div class="justify-content-center">
          <!-- <div style="padding-left: 0 !important;" class="row mb-2">
            <div class="col-md-4 mt-2 mb-1">
              <label for="category" class="label">Rodomu rezultatu skaicius</label>
              <select class="form-control">
                <option value="">Nuo 1 iki 10</option>
                <option value="">Nuo 11 iki 25</option>
                <option value="">Nuo 26 iki 40</option>
                <option value="">40 ir daugiau</option>
              </select>
            </div>
            <div style="padding-left: 0 !important;" class="col-md-4 mt-2 mb-1">
              <label for="category" class="label">Pasirinkite kategorija</label>
              <select class="form-control">
                <option value="">Visi rezultatai</option>
                <option value="">Nariai</option>
                <option value="">Registracijos</option>
                <option value="">Grupes</option>
              </select>
            </div>
          </div> -->

                <!-- <div class="card big">
                    <div class="card-header flex-inline">
                      <h2 class="vertical-align">Visi rasti nariai</h2>
                    </div>
                    <div class="card-body">
                        <table class="table card-table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Vardas</th>
                                    <th>Pavarde</th>
                                    <th>Gimimo data</th>
                                    <th>Istojo</th>
                                    <th>Veiksmai</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="alert alert-warning">
                            Rezultatu nerasta
                        </div>
                    </div>
                </div>

                <div class="card big">
                    <div class="card-header flex-inline">
                      <h2 class="vertical-align">Visos rastos registracijos</h2>
                    </div>
                    <div class="card-body">
                        <table class="table card-table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Vardas</th>
                                    <th>Pavarde</th>
                                    <th>Gimimo data</th>
                                    <th>Istojo</th>
                                    <th>Veiksmai</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="alert alert-warning">
                            Rezultatu nerasta
                        </div>
                    </div>
                </div> -->
                <div class="mb-4" v-if="search_results != null">
                  <div class="search-result mb-3" v-for="result in search_results.members" v-if="search_status == true">
                    <div>
                      <h2><router-link v-bind:to="{ name: 'edit', params: { id: result.id }}" class="item">{{result.firstName}} {{result.lastName}}</router-link></h2>
                      <!-- <router-link to="{path: '/member/edit/', params: {id: result.id}" class="tag tag-red"></router-link> -->
                      <h5 class="mt-2"></h5>
                    </div>
                    <div>
                      <label class="bg-label bg-label-search bg-label-search-success">Narys</label>
                    </div>
                  </div>

                  <div class="search-result mb-3" v-for="result in search_results.groups" v-if="search_status == true">
                    <div>
                      <h2><router-link v-bind:to="{ name: 'group', params: { id: result.id }}" class="item">{{result.groupName}}</router-link></h2>
                      <!-- <router-link to="{path: '/member/edit/', params: {id: result.id}" class="tag tag-red"></router-link> -->
                      <h5 class="mt-2"></h5>
                    </div>
                    <div>
                      <label class="bg-label bg-label-search bg-label-search-warning">Grupė</label>
                    </div>
                  </div>


                  <div class="search-result mb-3" v-for="result in search_results.signups" v-if="search_status == true">
                    <div>
                      <h2><router-link v-bind:to="{ name: 'sign_confirm', params: { id: result.id }}" class="item">{{result.firstName}} {{result.lastName}}</router-link></h2>
                      <!-- <router-link to="{path: '/member/edit/', params: {id: result.id}" class="tag tag-red"></router-link> -->
                      <h5 class="mt-2"></h5>
                    </div>
                    <div>
                      <label class="bg-label bg-label-search bg-label-search-danger">Registracija</label>
                    </div>
                  </div>

                </div>
        </div>
    </div>
</div>
</template>

<script>

  export default {
  	data(){
  		return{
  			API_results: [],
        signupsCount: null,
        signupsCount_date: null,
        income: null,
        income_date: null,
        outcome: 0,
        outcome_date: null,
        membersCount: null,
        membersCount_date: null,
        q: null,
        search_results: [],
        search_status: null,
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

      },
    },
    mounted() {

      this.$refs['search'].focus();

      axios.get('api/stats/signups/1').then(response => {
        this.signupsCount = response.data.count;
        this.signupsCount_date = response.data.date;
      });
      axios.get('api/stats/members/count').then(response => {
        this.membersCount = response.data.count;
      });
      axios.get('api/stats/economy/income/1').then(response => {
        this.income = response.data.income;
        this.income_date = response.data.date;
      });
    }
  }
</script>

<style>
</style>
