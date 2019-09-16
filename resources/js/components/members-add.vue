<template>
<div>
  <div class="page-header mb-4">
      <div class="description">
        <h3>Nariai</h3>
        <h1>Naujo nario anketa</h1>
      </div>
      <div class="ml-5 stats">
         <div class="actions">
          <router-link to="/members" class="btn btn-danger">
            <span>Atšaukti</span>
          </router-link>
        </div>
      </div>
    </div>

    <div class="page-content justify-content-center">
    <div class="card card-big">
       <div class="alert alert-success" v-if="operationStatus">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Operacija sekmingai atlikta!</h2>
        <h4>Džiaugiamės us Jus!</h4>
      </div>
      <div class="alert alert-danger" v-if="serverError">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Serverio klaida</h2>
        <h4>Pabandykite atlikti šį veiksmą po keletos minučių ir jei tai nepadeda susisiekite su techninio aptarnavimo komanda</h4>
        <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
      </div>
      <div class="alert alert-danger" v-if="alreadyExcists">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Toks narys jau egzistuoja</h2>
        <h4>Pasinaudokite paieška ir suraskite jį</h4>
        <h5 style="color: #fff;">Klaidos tekstas: {{serverErrorMessage}} </h5>
      </div>
       <div class="alert alert-warning" v-if="validationFailed">
        <h2 style="text-transform: uppercase;color: #fff;font-weight: bolder;">Neužpildyti visi būtini laukeliai</h2>
        <h4>Dar karta peržiūrėkite raudonai pažymėtus laukelius</h4>
      </div>
      <div class="card-body">
        <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control input-search" v-bind:class="{form_control_danger: fullName_required}" style="font-size: 1.8em; border: none;font-weight: bolder; padding: 2rem .7rem;padding-left:0;" placeholder="Vardas ir Pavarde" v-model="fullName">
                                <label class="text-danger" v-if="fullName == ''">Šis laukelis privalomas</label>
                            </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                                <div class="description">
                                  <h3>Asmeninė informacija</h3>
                                </div>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="inputBname">Miestas</label>
                            <select class="form-control form-control-select" v-model="city">
                              <option value="Klaipėda" selected>Klaipėda</option>
                              <option value="Vilnius">Vilnius</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="inputBname">Gimimo data</label>
                            <date-picker v-model="birthDate" :config="options"></date-picker>                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                                <div class="description">
                                  <h3>Kontaktinė informacija</h3>
                                </div>
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputBname">Telefono numeris</label>
                                <input type="text" class="form-control" id="inputBname" placeholder="+3706xxxxx" v-model="primaryPhone">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputBname">Antras telefono numeris</label>
                                <input type="text" class="form-control" id="inputBname" placeholder="+3706xxxxx" v-model="secondaryPhone" v-bind:class="{form_control_danger: secondaryPhone_required}">
                            </div>
                            <div class="form-group col-md-8">
                                <label for="inputBname">El. pašto adresas</label>
                                <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="email">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="inputBname">Facebook vartotojas</label>
                                <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="facebook">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputBname">Instagram vartotojas</label>
                                <input type="text" class="form-control" id="inputBname" placeholder="hello@sfinx.lt" v-model="instagram">
                            </div>
                        </div>
                         <div class="form-row mt-5 mb-0">
                              <div class="form-group col-md-6">
                                    <div class="description">
                                      <h3>Papildoma informacija</h3>
                                    </div>
                              </div>
                                <div class="form-group col-md-8">
                                    <input type="number" class="form-control" placeholder="Nuskaitykite RFID" v-model="rfid_id"/>
                                </div>
                            </div>
                          <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="inputBname">Pastabos, komentarai</label>
                                <textarea type="text" class="form-control" id="inputBname" placeholder="----APRASYMA RASYKITE CIA----" v-model="description"></textarea>
                            </div>
                          </div>

                        <a href="#" class="btn btn-primary" v-on:click="memberCreate">Pridėti nari</a>
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
        fullName: null,
        fullName_required: false,
        birthDate: null,
        birthDate_required: false,
        primaryPhone: null,
        primaryPhone_required: false,
        secondaryPhone: null,
        secondaryPhone_required: false,
        email: null,
        email_required: false,
        instagram: null,
        instagram_required: false,
        facebook: null,
        facebook_required: false,
        description: null,
        description_required: false,
        city: null,
        city_required: false,
        API_results: null,
        rfid_id: null,
        options: {
          format: 'YYYY-MM-DD',
          useCurrent: true,
        },
      }
    },
    mounted(){
      if(this.$route.params.id != null){
        axios.get('/api/signups/' + this.$route.params.id, {

        }).then(response => {
            this.API_results = response.data;
            this.fullName = response.data.firstName + " " + response.data.lastName;
            this.birthDate = response.data.birthDate;
            this.primaryPhone = response.data.primaryPhone;
            this.city = response.data.city;
            this.description = response.data.description;
            this.secondaryPhone = response.data.secondaryPhone;
            this.email = response.data.email;
        });
      }
    },
    methods: {
      memberCreate: function(){
        var verificationStatus = false;
        if(this.fullName == '' || this.fullName == null) { this.fullName_required = true; verificationStatus = false }
        // if(this.primaryPhone == '' || this.primaryPhone == null) { this.primaryPhone_required = true;verificationStatus = false }
        // if(this.birthDate == '' || this.birthDate == null) { this.birthDate_required = true; verificationStatus = false }
        // if(this.city == '' || this.city == null) { this.city_required = true; verificationStatus = false }
        var fullFullName = this.fullName.split(" ");
        axios.post('/api/members/store', {
          firstName: fullFullName[0],
          lastName: fullFullName[1],
          primaryPhone: this.primaryPhone,
          birthDate: this.birthDate,
          description: this.description,
          city: this.city,
          email: this.email,
          secondaryPhone: this.secondaryPhone,
          facebook: this.facebook,
          instagram: this.instagram,
          description: this.description,
          rfid_id: this.rfid_id
        }).then(response => {
          if (response.data.status == 'OK')
          {
            if(this.$route.name == "add") {
              this.$router.push("/members");
              return;
            }
            this.operationStatus = true;
            axios.post('/signups/delete', {
              id: this.$route.params.id
            }).then(response => {
              if(response.status != 200) swal("SgN-del Klaida", "Ivyko SgN-del klaida. Praneskite techniniam personalui!", "error");
              else setTimeout(this.$router.push('/signups'), 3000);
            });

          }else{
            if(response.data.cause == 1){
              this.validationFailed = true;
            }else{
              this.serverError = true;
              this.serverErrorMessage = response.data;
            }

            if(response.data.cause == 3){this.alreadyExcists = true}
          }
        });
      }
    }
  }
</script>

<style>
</style>
