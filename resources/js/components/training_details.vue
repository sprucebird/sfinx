<template>
  <div>
	<div class="page-header mb-1">
      <div class="description">
        <h3>Sfinx Squad 2019-10-12 14:57</h3>
        <h1>Treniruotės apžvalga</h1>
      </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="row justify-content-center">
      <div class="card col-md-6">
        <div class="card-body text-center">
            <div class="h6">Treniruotės lankomumas</div>
            <h1 class="font-weight-bold mb-4">{{proc_rate}}%</h1>
              <div class="progress progress-sm">
                <div v-if="parseFloat(proc_rate) > 60" class="progress-bar bg-green" role="progressbar" v-bind:style="{width: parseFloat(proc_rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                <div v-if="parseFloat(proc_rate) >= 30 && parseFloat(proc_rate) <= 60" class="progress-bar bg-yellow" role="progressbar" v-bind:style="{width: parseFloat(proc_rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                <div v-if="parseFloat(proc_rate) < 30" class="progress-bar bg-red" role="progressbar" v-bind:style="{width: parseFloat(proc_rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
        </div>
      </div>
    </div>
    <div class="card big mt-5">
      <div class="card-header">
        Neapsilankę nariai
      </div>
      <div class="card-body">
       <div class="table-responsisve">
                    <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                      <thead>
                        <tr role="row">
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 200px;">Narys</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 100px;">Telefono numeris</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 150px;">Pask. kartą užfiksuotas</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Lankomumas</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Narys nuo</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 150px;"> </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr role="row" class="odd" v-for="goner in API_results">
                          <td>
                            <div>
                              {{goner.firstName}}&nbsp;{{goner.lastName}}
                            </div>
                            <div class="small text-muted">
                              {{goner.groupName}}
                            </div>
                          </td>
                          <td>
                            {{goner.primaryPhone}}
                          </td>
                          <td>
                            {{(goner.lastVisited != null ? goner.lastVisited : "N/A")}}
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-left">
                                <strong>{{goner.rate}}%</strong>
                              </div>
                              <div class="float-right">
                                <small class="text-muted">nuo {{goner.created_at}}</small>
                              </div>
                            </div>
                            <div class="progress progress-xs">
                              <div v-if="parseFloat(goner.rate) > 60" class="progress-bar bg-green" role="progressbar" v-bind:style="{width: parseFloat(goner.rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                              <div v-if="parseFloat(goner.rate) >= 30 && parseFloat(goner.rate) <= 60" class="progress-bar bg-yellow" role="progressbar" v-bind:style="{width: parseFloat(goner.rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                              <div v-if="parseFloat(goner.rate) < 30" class="progress-bar bg-red" role="progressbar" v-bind:style="{width: parseFloat(goner.rate) + '%' }" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            {{goner.created_at}}
                          </td>
                          <td class="text-center">
                            <div class="item-action dropdown">
                              <router-link v-bind:to="{ name: 'edit', params: { id: goner.id }}" ><a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="icon fe fe-more mr-3"></i> Redaguoti</a></router-link>
                            </div>
                          </td>
                        </tr>

                      </tbody>
                    </table>
        </div>
        <!-- <div class="alert alert-primary">
          Atsiprašome, šiuo metu veiksmų centras yra tik kūrimo stadijoje
        </div> -->
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
        proc_rate: 0
  		}
  	},
    mounted() {
      axios.post('/api/gonerList', {'id': this.$route.params.id}).then(response => {
        this.API_results = response.data.goners;
        this.proc_rate = this.$route.params.proc_rate;
      });
    },
  }
</script>

<style>
</style>
