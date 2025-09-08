<template>
   <header class="mb-4">
      <div class="header-top">
          <div class="container">
              <div class="logo">
                  <h2 class="mb-0 text-warning font-bold fs-4">CAT<span class="text-primary">System</span></h2>
              </div>
              <div>
                  <Timer :startTime="start_time" :durasi="durasi" @time-up="submitExam">-</Timer>
              </div>
              <div class="header-top-right">
                  <button class="btn btn-primary" @click="confirmSubmitExam" :disabled="onProses">
                    <span v-if="onProses">
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
                        <i class="bi bi-check-square"></i> Selesai Ujian
                    </span>
                  </button>
                  <div class="dropdown">
                      <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                          <div class="user-menu d-flex">
                              <div class="user-name text-end me-3">
                                  <h6 class="mb-0 text-gray-600">{{ nama_user }}</h6>
                                  <p class="mb-0 text-sm text-gray-600">{{ role }}</p>
                              </div>
                              <div class="user-img d-flex align-items-center">
                                  <div class="avatar avatar-md">
                                      <img :src="`/app/assets/compiled/jpg/1.jpg`">
                                  </div>
                              </div>
                          </div>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
                          <li>
                              <h6 class="dropdown-header">Hello,  </h6>
                          </li>
                          <li><a class="dropdown-item text-danger" href="{{route('login.logout')}}"><i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                      </ul>
                  </div>

                  <!-- Burger button responsive -->
                  <a href="#" class="burger-btn d-block d-xl-none">
                      <i class="bi bi-justify fs-3"></i>
                  </a>
              </div>
          </div>
      </div>

  </header>
    <div class="container">
      <!-- <Timer :initialTime="3600" @time-up="submitExam" /> -->
        <div class="row">

            <div class="col-md-8">
                <Question :soal="soals[currentQuestion]" @save="saveAnswer" @skip="skipQuestion" :selected="currentQuestion" :isproses="onProses"/>    
            </div>
            <div class="col-md-4">
                <div class="card">
                    <ListSoal :soals="soals" :level_kompetensis="level_kompetensis" @select-soal="changeQuestion" :selected="currentQuestion" />
                </div>
            </div>
        </div>
      
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import Timer from '../../Components/Ujian/Timer.vue';
  import ListSoal from '../../Components/Ujian/ListSoal.vue';
  import Question from '../../Components/Ujian/KontenSoal.vue';
  
  export default {
    components: { Timer, ListSoal, Question },
    props: ['soals','level_kompetensis','ujian','nama_user','role','start_time','durasi','ujian_peserta_id'],
    setup(props) {
      const currentQuestion = ref(0);
      const answers = ref({});
      const onProses= ref(false);
     
      const changeQuestion = (index) => {
        currentQuestion.value = index;
      };
  

    const saveAnswer = (data) =>  {
        onProses.value = true;
        Inertia.post('/ujian/jawab', {
            ujian_peserta_id: props.ujian_peserta_id,
            ujian_soal_id: data.soal_id,
            jawaban: data.jawaban,
        }, {
            preserveScroll: true,
            preserveState: true,  
            onSuccess: () => {
                onProses.value = false;
                Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        showConfirmButton: false,
                        text: "Jawaban berhasil disimpan!",
                        icon: "success",
                        timer: 2000
                });
                skipQuestion();
                
            },
            onError: (errors) => {
                onProses.value = false;
                console.error(errors)
                if (errors.jawaban) {
                  Swal.fire('Info', 'Anda belum memilih jawaban', 'info')
                } else {
                  Swal.fire('Gagal', 'Terjadi kesalahan validasi', 'error')
                }
            }
        })
    };
  
      const skipQuestion = () => {
        if (currentQuestion.value < props.soals.length - 1) {
          currentQuestion.value++;
        }
      };
  
      const confirmSubmitExam = () => {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Silahkan periksa kembali sebelum menyelesaikan ujian",
            icon: "warning",
            allowOutsideClick: false,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Selesai',
            cancelButtonText: 'No'
            }).then((result) => {
            if (result.isConfirmed) {
               submitExam();
            }
        })
      };

      const submitExam = () => {
        onProses.value = true;
        Inertia.post('/ujian/selesai', {},{
            preserveScroll: true,
            preserveState: true,  
            onSuccess: () => {
                onProses.value = false;
                Swal.fire({
                title: "Success!",
                        text: 'Ujian telah diselesaikan',
                        icon: "success",
                        timer: 2000
                }).then((result) => {
                    window.location.href = '/ujian/hasil/'+props.ujian_peserta_id;

                });
                
            },
            onError: (errors) => {
                onProses.value = false;
                Swal.fire('Gagal', 'Terjadi kesalahan validasi', 'error')
            }
        });
      };
      
  
      return { currentQuestion,onProses, answers, changeQuestion, saveAnswer, skipQuestion, submitExam, confirmSubmitExam };
    },
    
  };
  </script>