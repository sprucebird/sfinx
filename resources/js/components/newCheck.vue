<template>
<div>
  <div class="page-header mb-4">
      <div class="description">
        <h3>Praėjimo kontrolė</h3>
        <h1>Pridėkite kortelę prie skaitytuvo</h1>
      </div>
    </div>

    <div class="page-content justify-content-center">
      <div class="col-md-12">
        <input ref="in1" type="text" class="form-control input-search text-center" style="font-size: 1.8em; border: none;font-weight: bolder; padding: 2rem .7rem;padding-left:0;letter-spacing: 10px;background: none !important;" placeholder="Užveskite pelę ant šio laukelio" v-model="code" @keyup.enter="userCreate">
      </div>
    <div class="col-md-7 card big mt-5" v-if="operationStatus == true">
      <div class="card-header">
        <div class="h5">
          Nario duomenys
        </div>
      </div>
      <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <label for="fname">Vardas</label>
                <div class="h4">
                  {{ownerData.firstName}}
                </div>
              </div>
              <div class="col-md-6">
                <label for="fname">Pavardė</label>
                <div class="h4">
                  {{ownerData.lastName}}
                </div>
              </div>
            </div>
            <div class="alert alert-success" v-if="ownerData.balance > 0">
             <div style="text-transform: uppercase;font-weight: bolder;">Apmokėta</div>
           </div>
           <div class="alert alert-info" v-if="ownerData.balance == 0">
            <div style="text-transform: uppercase;font-weight: bolder;">Nėra apmokėta už šį mėnesį</div>
          </div>
           <div class="alert alert-danger" v-if="ownerData.balance < 0">
             <div style="text-transform: uppercase;font-weight: bolder;">Nėra apmokėta</div>
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
          <button class="btn btn-secondary" @click="pay()">Apmoketi</button>
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
      pay() {
        this.$parent.newPayment(this.ownerData.id, this.ownerData);
      },
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
