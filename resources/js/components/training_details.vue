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
            <h1 class="font-weight-bold mb-4">62%</h1>
              <div class="progress progress-sm">
                  <div class="progress-bar bg-yellow" style="width: 62%"></div>
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
                        <tr role="row" class="odd">
                          <td>
                            <div>
                              Vardenis Pavardenis
                            </div>
                            <div class="small text-muted">
                              Sfinx Squad
                            </div>
                          </td>
                          <td>
                            +370 611 48 350
                          </td>
                          <td>
                            2019-09-05 17:40:05
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-left">
                                <strong>22%</strong>
                              </div>
                              <div class="float-right">
                                <small class="text-muted">nuo 2019-09-05 17:40:05</small>
                              </div>
                            </div>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-red" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            2019-09-02 15:45:01
                          </td>
                          <td class="text-center">
                            <div class="item-action dropdown">
                              <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="icon fe fe-more mr-3"></i> Redaguoti</a>
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
  		}
  	},
    mounted() {
      axios.get('api/entries/all').then(response => {
        this.API_results = response.data.entries;
      });

    }
  }
</script>

<style>
</style>
