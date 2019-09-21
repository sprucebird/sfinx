<template>
<div>
  <div class="page-header mb-4">
      <div class="description">
        <h3>Praėjimo kontrolė</h3>
        <h1>Treniruotė</h1>
      </div>
      <!-- <div class="ml-5 stats">
         <div class="actions">
          <router-link to="/users" class="btn btn-danger">
            <span>Atšaukti</span>
          </router-link>
        </div>
      </div> -->
    </div>

    <div class="page-content justify-content-center">
    <div class="card card-big">
       <div class="alert alert-success" v-if="operationStatus">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">{{ownerData.firstName}} {{ownerData.lastName}}</h2>
        <span class="bg-label bg-label-success mb-2" v-if="ownerData.balance > 0">Apmoketa</span>
        <span class="bg-label bg-label-danger mb-2" v-if="ownerData.balance <= 0">Nepmoketa</span>
        <h4>Šis įvykis buvo įrašytas į sistemą.</h4>
      </div>
      <div class="alert alert-warning" v-if="notFound">
       <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Narys su tokia kortele nerastas</h2>
       <h4>Šis įvykis buvo įrašytas į sistemą.</h4>
     </div>
      <div class="alert alert-danger" v-if="serverError">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Serverio klaida</h2>
        <h4>Pabandykite atlikti šį veiksmą po keletos minučių ir jei tai nepadeda susisiekite su techninio aptarnavimo komanda</h4>
        <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
      </div>
      <div class="card-body">
        <div class="col-md-12">
                          <div class="form-row">
                            <div class="form-group col-md-12">
                                <h2>Pridėkite kortelę prie skaitytuvo</h2>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input ref="in1" type="text" class="form-control input-search" style="font-size: 1.8em; border: none;font-weight: bolder; padding: 2rem .7rem;padding-left:0;letter-spacing: 10px;background: none !important;" placeholder="Naujas kodas atsiras cia" v-model="code" @keyup.enter="userCreate">
                            </div>
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
        operationStatus: false,
        serverError: null,
        serverErrorMessage: null,
        validationFailed: null,
        alreadyExcists: false,
        notFound: null,
        code: null,
        ownerData: null
      }
    },
    mounted(){
      //this.$refs.input[0].focus();
    },
    methods: {
      userCreate: function(){
        axios.post('/rfid/scan', {
          RFID: this.code
        }).then(response => {
          if (response.data.status == 'OK')
          {
            this.operationStatus = true;
            //setTimeout(this.operationStatus = false, 9000);
            this.code = response.data.code;

            this.ownerData = response.data.owner;

            if(this.ownerData == null)
            {
              this.operationStatus = false;
              this.notFound = true;
              setTimeout(this.notFound = false, 9000);
            }


          }else{
              this.serverError = true;
              setTimeout(this.serverError = false, 9000);
              this.serverErrorMessage = response.data;
          }
        });
      }
    }
  }
</script>

<style>
</style>
