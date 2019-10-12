<template>
  <div>
	<div class="page-header mb-1">
      <div class="description">
        <h3>Praėjimo kontrolė</h3>
        <h1>Treniruočių statistika</h1>
      </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="pl-3 row">
      <div class="card col-md-3">
        <div class="card-body text-center">
            <div class="h6">Studijos lankomumas</div>
            <h1 class="font-weight-bold mb-4">78%</h1>
              <div class="progress progress-sm">
                  <div class="progress-bar bg-yellow" style="width: 78%"></div>
              </div>
        </div>
      </div>
      <div class="card col-md-3">
        <div class="card-body text-center">
            <div class="h6">Neaktyvūs nariai</div>
            <h1 class="font-weight-bold mb-4">12%</h1>
              <div class="progress progress-sm">
                  <div class="progress-bar bg-red" style="width: 12%"></div>
              </div>
        </div>
      </div>
    </div>
    <div class="card big mt-5">
      <div class="card-header">
        Įvykusios treniruotės
      </div>
      <div class="card-body">
       <div class="table-responsisve">
                    <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                      <thead>
                        <tr role="row">
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 200px;">Treniruotė</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 100px;">Data</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 150px;">Laikas</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Apsilankė %</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Neapsilankė narių</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 150px;"> </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr role="row" class="odd">
                          <td>
                            <div>
                              Sfinx Squad
                            </div>
                            <div class="small text-muted">
                              21 nariai
                            </div>
                          </td>
                          <td>
                            2019-10-12
                          </td>
                          <td>
                            <div>
                              <span class="tag tag-grey mr-1">14:57</span>
                              <span class="text-muted">-</span>
                              <span class="tag tag-grey ml-1">15:57</span>
                            </div>
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-left">
                                <strong>62%</strong>
                              </div>
                              <div class="float-right">
                                <small class="text-muted">nuo 14:41 iki 00:00</small>
                              </div>
                            </div>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-green" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td>
                            <div class="clearfix">
                              <div class="float-left">
                                <strong>3 narių</strong>
                              </div>
                              <div class="float-right">
                                <small class="text-muted">tai yra 12% visų grupės narių</small>
                              </div>
                            </div>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-yellow" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                          <td class="text-center">
                            <div class="item-action dropdown">
                              <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="icon fe fe-activity mr-3"></i> Plačiau</a>
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
