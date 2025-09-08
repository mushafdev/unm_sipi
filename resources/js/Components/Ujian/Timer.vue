<template>
    <h3 class="timer">
      {{ formatTime(timeLeft) }}
    </h3>
  </template>
  
  <script>
  import { ref, onMounted, onUnmounted } from 'vue';
  
  export default {
    props: ['startTime', 'durasi'],
    emits: ['time-up'],
    setup(props, { emit }) {
      const timeLeft = ref(0);
      let timer = null;

      const calculateTimeLeft = () => {
        const start = new Date(props.startTime).getTime();
        const now = Date.now(); // Waktu saat ini di klien
        const elapsed = Math.floor((now - start) / 1000); // Selisih dalam detik
        const totalDuration = props.durasi; // Durasi total dalam detik
        const remaining = totalDuration - elapsed;

        return remaining > 0 ? remaining : 0;
      };

      const updateTime = () => {
        timeLeft.value = calculateTimeLeft();
        if (timeLeft.value <= 0) {
          emit('time-up');
          clearInterval(timer);
        }
      };

      onMounted(() => {
        timeLeft.value = calculateTimeLeft();
        timer = setInterval(updateTime, 1000); 
      });

      onUnmounted(() => {
        clearInterval(timer);
      });

      const formatTime = (sisaWaktu) => {
        const totalSeconds = Math.floor(sisaWaktu);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
      };

      return { timeLeft, formatTime };
    },
  };
  </script>