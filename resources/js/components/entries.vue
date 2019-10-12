<template>
  <div>
	<div class="page-header mb-1">
      <div class="description">
        <h3>Praėjimo kontrolė</h3>
        <h1>Treniruočių statistika</h1>
      </div>
  </div>
  <div class="page-content justify-content-center mt-4">
    <div class="card big mt-5">
      <div class="card-header">
        Įvykusios treniruotės
      </div>
      <div class="card-body">
       <div class="table-responsisve">
                    <table class="table card-table table-vcenter text-nowrap datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                      <thead>
                        <tr role="row">
                          <th class="w-1 sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="No.: activate to sort column descending" style="width: 45px;">Unik. Nr.</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="VAT No.: activate to sort column ascending" style="width: 81px;">Grupė</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Invoice Subject: activate to sort column ascending" style="width: 171px;">Data</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 200px;">Apsilankė %</th>
                          <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 146px;">Neapsilankė narių</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr role="row" class="odd">
                          <td>TR-00001</td>
                          <td>Sfinx Adults</td>
                          <td>2019-10-12 14:57 - 15:47</td>
                          <td class="text-center">
                            <div class="mx-auto chart-circle chart-circle-xs" data-value="0.62" data-thickness="3" data-color="blue">
                              <canvas width="40" height="40"></canvas>
                              <div class="chart-circle-value">62%</div>
                            </div>
                          </td>
                          <td>6</td>
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
