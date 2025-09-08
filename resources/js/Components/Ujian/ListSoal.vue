<template>
    <!-- <p>Selected index: {{ selected }}</p> -->
    <div class="card-body">
      <div v-for="(level, levelIndex) in level_kompetensis" :key="level.id" class="mb-2">
        <h6 class="text-left mb-2">{{level.level_kompetensi }}</h6>
  
        <div class="d-flex flex-wrap gap-2">
          <div
            v-for="(soal, index) in soalPerLevel[level.id]"
            :key="soal.id"
            class="text-center soal-kotak"
            :class="{
                'active-soal': getGlobalIndex(level.id, index) === selected,
                'bg-success text-white': soal.jawaban,
                'bg-danger text-white': !soal.jawaban
            }"
            @click="$emit('select-soal', getGlobalIndex(level.id, index))"
            style=" cursor: pointer;"
          >
            <div class="card-body p-2">
              {{ getGlobalIndex(level.id, index) + 1 }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['soals', 'level_kompetensis', 'selected'],
    emits: ['select-soal'],
    computed: {
      soalPerLevel() {
        const map = {}
        for (const level of this.level_kompetensis) {
          map[level.id] = []
        }
        for (const soal of this.soals) {
          if (map[soal.level_kompetensi_id]) {
            map[soal.level_kompetensi_id].push(soal)
          }
        }
        return map
      },
      flatSoalList() {
        // daftar soal diurutkan berdasarkan level_kompetensis
        const list = []
        for (const level of this.level_kompetensis) {
          const soals = this.soalPerLevel[level.id] || []
          for (const soal of soals) {
            list.push(soal)
          }
        }
        return list
      }
    },
    // watch: {
    //     selected(newVal) {
    //         console.log('Selected berubah ke:', newVal);
    //     }
    // },
    methods: {
      getGlobalIndex(levelId, indexInLevel) {
        let index = 0
        for (const level of this.level_kompetensis) {
          if (level.id === levelId) break
          index += this.soalPerLevel[level.id]?.length || 0
        }
        return index + indexInLevel
      }
    }
  }
  </script>
  
  <style scoped>
  .soal-kotak {
    height: 25px;
    width: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 100%;
    font-size: 8pt;
  }
  .active-soal{
    border: 2px solid #ffc008 !important;
    box-shadow: 0px 1px 12px 6px #ffc008;
  }
  </style>
  