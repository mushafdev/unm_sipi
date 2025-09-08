<template>
    <div class="card">
        <div class="card-body">
            <h6 class="badge bg-info">{{(selected+1)}}</h6>
            <p v-html="soal.soal_teks"></p>
            <div class="mt-3" v-for="(option, index) in soal.pilihan" :key="option.kode">
                <div class="form-check">
                    <input class="form-check-input" type="radio" :id="'option-' + soal.id + '-' + index" v-model="selectedOption" :value="option.kode"/>
                    <label :for="'option-' + soal.id + '-' + index" class="form-check-label">
                        {{ option.kode }}. {{ option.teks }}
                    </label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success btn-md" @click="$emit('save',{soal_id:soal.id,jawaban:selectedOption})" :disabled="isproses">
                <span v-if="isproses">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Menyimpan...
                </span>
                <span v-else>
                    <i class="bi bi-save"></i> Simpan dan Lanjutkan
                </span>
            </button>
            <button class="btn btn-warning btn-md ms-1" @click="$emit('skip')"> <i class="bi bi-forward"></i> Lewati</button>
        </div>
    </div>
</template>
  
<script>
  import { ref } from 'vue';
  
  export default {
    props: ['soal','selected','isproses'],
    emits: ['save', 'skip','select-soal'],
    data() {
        return {
            selectedOption: this.soal.jawaban || null,
        };
    },
    watch: {
        soal: {
            handler(newSoal) {
            this.selectedOption = newSoal.jawaban || null;
            },
            immediate: true,
            deep: true
        },
        // isproses(newVal) {
        //     console.log('Status proses:', newVal);
        // }
    },
    methods: {
    },
    setup() {
        // console.log(soal.pilihan);
    //   const selectedOption = ref(null);
    //   return { selectedOption };
    },
  };
</script>